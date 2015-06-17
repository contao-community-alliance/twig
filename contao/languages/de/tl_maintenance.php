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
 * Jobs
 */
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['twig'] = array(
    'Twig Cache leeren',
    'Leert den Twig Cache. Der Twig Cache kann durch aktivieren des (Twig) Debug Modus in den Backend-Einstellungen dauerhaft deaktiviert werden.'
);

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_maintenance']['purgeTwigCache']   = 'Twig Cache löschen';
$GLOBALS['TL_LANG']['tl_maintenance']['doPurgeTwigCache'] = 'Cache jetzt löschen';
$GLOBALS['TL_LANG']['tl_maintenance']['twigCacheCount']   = '%d Dateien im Cache, %s';
$GLOBALS['TL_LANG']['tl_maintenance']['purgedTwigCache']  = '%d Dateien wurden aus dem Cache gelöscht.';
