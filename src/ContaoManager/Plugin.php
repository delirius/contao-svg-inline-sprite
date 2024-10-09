<?php

declare(strict_types=1);

/*
 * This file is part of SVG Inline.
 *
 * (c) Daniel Herren 2024 <contao@delirius.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/delirius/contao-svg-inline
 */

namespace Delirius\ContaoSvgInline\ContaoManager;

use Delirius\ContaoSvgInline\DeliriusContaoSvgInline;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    /**
     * @return array
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(DeliriusContaoSvgInline::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
