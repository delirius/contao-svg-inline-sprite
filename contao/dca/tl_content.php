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

use Contao\Backend;
use Contao\DataContainer;
use Delirius\ContaoSvgInline\Controller\ContentElement\SvgInlineController;

/**
 * Content elements
 */
$GLOBALS['TL_DCA']['tl_content']['palettes'][SvgInlineController::TYPE] = '{type_legend},type;{image_legend},singleSRC,svg_width,svg_height,svg_comments;{svgsprite_legend},svg_use_symbol;{link_legend:hide},url,target,titleText;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'svg_use_symbol';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['svg_use_symbol'] = 'svg_symbol_id,svg_nocache';

$GLOBALS['TL_DCA']['tl_content']['fields']['singleSRC']['eval']['extensions'] = 'svg';
$GLOBALS['TL_DCA']['tl_content']['fields']['singleSRC']['eval']['submitOnChange'] = true;
$GLOBALS['TL_DCA']['tl_content']['fields']['url']['eval']['mandatory'] = false;

$GLOBALS['TL_DCA']['tl_content']['fields']['svg_width'] = array
	(
	'inputType' => 'text',
	'eval' => array('rgxp' => 'natural', 'nospace' => true, 'tl_class' => 'clr w25', 'style' => 'background: var(--form-bg) url(system/themes/flexible/icons/hints.svg) no-repeat right 1px top 2px;'),
	'sql' => "varchar(16) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_content']['fields']['svg_height'] = array
	(
	'inputType' => 'text',
	'eval' => array('rgxp' => 'natural', 'nospace' => true, 'tl_class' => 'w25', 'style' => 'background: var(--form-bg) url(system/themes/flexible/icons/hints.svg) no-repeat right 1px top -28px;'),
	'sql' => "varchar(16) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['svg_comments'] = array
	(
	'inputType' => 'checkbox',
	'eval' => array('tl_class' => 'clr w50 m12 cbx'),
	'sql' => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['svg_nocache'] = array
	(
	'inputType' => 'checkbox',
	'eval' => array('tl_class' => 'w50 m12 cbx'),
	'sql' => "char(1) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_content']['fields']['svg_use_symbol'] = array
	(
	'inputType' => 'checkbox',
	'eval' => array('submitOnChange' => true),
	'sql' => array('type' => 'boolean', 'default' => false),
);

$GLOBALS['TL_DCA']['tl_content']['fields']['svg_symbol_id'] = array
	(
	'inputType' => 'radio',
	'options_callback' => array('tl_content_ext', 'getSymbols'),
	'eval' => array('cols' => 4, 'tl_class' => 'clr long'),
	'sql' => "varchar(128) NOT NULL default ''",
);

/**/
class tl_content_ext extends Backend {

	public function getSymbols(DataContainer $dc) {
		$arrSymbols = [];

		$objFile = \Contao\FilesModel::findByUuid($dc->activeRecord->singleSRC);
		if ($objFile === \null  || $objFile->found == 0) {
			return $arrSymbols;
		}

		$xml = simplexml_load_file($objFile->path);
		$xml->registerXPathNamespace('svg', 'http://www.w3.org/2000/svg');

		foreach ($xml->xpath('//svg:symbol') as $symbol) {
			$sId = (string) $symbol->attributes()->id;
			$arrSymbols[$sId] = '<svg height="50" width="50"><use href="' . $objFile->path . ($dc->activeRecord->svg_nocache ? '?v=' . rand(1000, 9000) : '') . '#' . $sId . '"></use></svg>';
		}

		return $arrSymbols;
	}
}