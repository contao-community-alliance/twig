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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['twigDebugMode']      = array(
    'Activate debug mode',
    'Activate the debug mode of the template engine (this can also be done with the contao debug mode). In debug mode the <code>dump</code> function is availeable and all templates are live rendered.<br><strong>Warning: The render process will be extremely slow in this mode!</strong>'
);
$GLOBALS['TL_LANG']['tl_settings']['twigDebugExtension'] = array(
    'Activate debug extension',
    'Activate the <code>dump</code> function even if debug mode is disabled.'
);

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['twig_legend'] = 'Twig Template Engine';
