<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/bit3/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{twig_legend:hide},twigDebugMode,twigDebugExtension';

$GLOBALS['TL_DCA']['tl_settings']['fields']['twigDebugMode'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['twigDebugMode'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['twigDebugExtension'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['twigDebugExtension'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
);
