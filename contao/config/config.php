<?php

/*
 * This file is part of SVG Inline.
 *
 * (c) Daniel Herren 2024 <contao@delirius.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/delirius/contao-svg-inline
 */
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

// Style sheet
if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))) {
	$GLOBALS['TL_CSS'][] = 'bundles/deliriuscontaosvginline/css/styles.css';
}