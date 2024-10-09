<?php

declare (strict_types = 1);

/*
 * This file is part of SVG Inline.
 *
 * (c) Daniel Herren 2024 <contao@delirius.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/delirius/contao-svg-inline
 */

namespace Delirius\ContaoSvgInline\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\String\HtmlAttributes;
use Contao\StringUtil;
use Contao\Template;
use Contao\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(category: 'media')]
class SvgInlineController extends AbstractContentElementController {
	public const TYPE = 'svg_inline';

	public function __construct(
		private readonly InsertTagParser $insertTagParser
	) {
	}

	protected function getResponse(Template $template, ContentModel $model, Request $request): Response {

		if ( ! $model->singleSRC) {
			return $template->getResponse();
		}

		$objFile = \Contao\FilesModel::findByUuid($model->singleSRC);
		if ($objFile === \null  || $objFile->found == 0) {
			return $template->getResponse();
		}

		$arrSize = [$model->svg_width, $model->svg_height];
		$svgOutput = '';
		$broken_image = '<svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#cc3333"><path d="M95-95v-368l136 136 166-166 166 165 165-166 138 138v261H95Zm0-771h771v374L729-628 563-462 397-627 231-461 95-598v-268Z"/></svg>';

		if ($model->svg_use_symbol) {
			// sprite symbol
			$type = 'svg-symbol';
			$xml = simplexml_load_file($objFile->path);
			$xml->registerXPathNamespace('svg', 'http://www.w3.org/2000/svg');

			$viewbox = '';
			if ($arrSymbol = $xml->xpath('//svg:symbol[@id="' . $model->svg_symbol_id . '"]')) {
				$viewbox = (string) $arrSymbol[0]->attributes()->viewBox;

				$svgAttributes = (new HtmlAttributes())
					->set('viewBox', $viewbox)
					->setIfExists('width', $arrSize[0])
					->setIfExists('height', $arrSize[1])
				;

				$template->set('svg_path', $objFile->path . ($model->svg_nocache ? '?v=' . rand(1000, 9000) : ''));
				$template->set('use_symbol', $model->svg_use_symbol);
				$template->set('svg_attributes', $svgAttributes);
				$template->set('svg_symbol_id', '#' . $model->svg_symbol_id);

				$svgOutput = $xml->asXML();
				if ($model->svg_comments) {
					$svgOutput = $this->removeComments($svgOutput);
				}
				$template->set('svg_symbol_inline', $svgOutput);

			} else {
				$template->set('svg_inline', $broken_image);

			}
		} else {
			// svg inline
			$type = 'svg-inline';

			$xml = simplexml_load_file($objFile->path);

			// check for symbols
			if (preg_match('/<symbol.*?>.*?<\/symbol>/is', $xml->asXML())) {
				$svgOutput = $broken_image;

			} else {

				if ($arrSize[0] || $arrSize[1]) {
					unset($xml->attributes()->width);
					unset($xml->attributes()->height);
					($arrSize[0] > 0 ? $xml->addAttribute("width", $arrSize[0]) : '');
					($arrSize[1] > 0 ? $xml->addAttribute("height", $arrSize[1]) : '');
				}

				$svgOutput = $xml->asXML();
				if ($model->svg_comments) {
					$svgOutput = $this->removeComments($svgOutput);
				}

			}
			$template->set('svg_inline', $svgOutput);

		}

		// cssID set htmlAttribute
		$cssID = StringUtil::deserialize($model->cssID);
		$wrapperAttributes = (new HtmlAttributes())
			->addClass('content-' . $type)
			->setIfExists('id', $cssID[0])
			->addClass($cssID[1])
		;
		$template->set('wrapper_attributes', $wrapperAttributes);

		// Link with attributes
		$href = $this->insertTagParser->replaceInline($model->url ?? '');

		if (Validator::isRelativeUrl($href)) {
			$href = $request->getBasePath() . '/' . $href;
		}

		$linkAttributes = (new HtmlAttributes())
			->set('href', $href)
			->setIfExists('title', $model->titleText)
		;

		if ($model->target) {
			$linkAttributes
				->set('target', '_blank')
				->set('rel', 'noreferrer noopener')
			;
		}

		$template->set('href', $href);
		$template->set('link_attributes', $linkAttributes);

		return $template->getResponse();
	}

	public function removeComments($svgRaw) {

		$svgRaw = preg_replace('/<\?xml.*\?>/', '', $svgRaw, 1);
		$svgRaw = preg_replace('/<!--.*-->/', '', $svgRaw, 1);
		return $svgRaw;

	}
}
