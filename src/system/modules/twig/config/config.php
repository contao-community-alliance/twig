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

include(TL_ROOT . '/plugins/twig/lib/Twig/Autoloader.php');
// Twig_Autoloader::register();
ini_set('unserialize_callback_func',
        'spl_autoload_call');
spl_autoload_register(array(new Twig_Autoloader, 'autoload'),
                      true,
                      true);
spl_autoload_register('__autoload',
                      true);

/**
 * Maintenance
 */
$GLOBALS['TL_MAINTENANCE'][] = 'PurgeTwigCache';
