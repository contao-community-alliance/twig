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
	'Debugmodus aktivieren',
	'Wechselt die Template Engine in den Debug Modus (dies geschieht auch durch den Contao Debug Modus). Im Debug Modus steht die <code>dump</code> Funktion zur Verfügung und Templates werden immer neu generiert.<br><strong>Achtung: Es ist mit einer deutlich langsameren Render-Geschwindigkeit zu rechnen!</strong>'
);
$GLOBALS['TL_LANG']['tl_settings']['twigDebugExtension'] = array(
	'Debug-Erweiterung aktivieren',
	'Aktiviert die <code>dump</code> Funktion auch bei deaktiviertem Debugmodus.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['twig_legend'] = 'Twig Template Engine';
