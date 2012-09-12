<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/InfinitySoft/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Jobs
 */
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['twig']    = array('Clean Twig cache',
                                                              'Clean the Twig cache. The Twig cache can be disabled with the (Twig) debug mode in the backend settings.');


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_maintenance']['purgeTwigCache']   = 'Clear Twig cache';
$GLOBALS['TL_LANG']['tl_maintenance']['doPurgeTwigCache'] = 'clear cache now';
$GLOBALS['TL_LANG']['tl_maintenance']['twigCacheCount']   = '%d files in the cache, %s';
$GLOBALS['TL_LANG']['tl_maintenance']['purgedTwigCache']  = '%d files are deleted from the cache.';
