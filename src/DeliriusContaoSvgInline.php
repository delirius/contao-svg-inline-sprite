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

namespace Delirius\ContaoSvgInline;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DeliriusContaoSvgInline extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
