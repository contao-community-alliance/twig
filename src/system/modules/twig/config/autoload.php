<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Twig
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'ContaoTwigLoaderFilesystemCached' => 'system/modules/twig/ContaoTwigLoaderFilesystemCached.php',
	'ContaoTwigCache'                  => 'system/modules/twig/ContaoTwigCache.php',
	'TwigBackendTemplate'              => 'system/modules/twig/TwigBackendTemplate.php',
	'TwigSimpleHybrid'                 => 'system/modules/twig/TwigSimpleHybrid.php',
	'PurgeTwigCache'                   => 'system/modules/twig/PurgeTwigCache.php',
	'TwigHelper'                       => 'system/modules/twig/TwigHelper.php',
	'ContaoTwigGlobalAccessObject'     => 'system/modules/twig/ContaoTwigGlobalAccessObject.php',
	'TwigModule'                       => 'system/modules/twig/TwigModule.php',
	'TwigCustomPagination'             => 'system/modules/twig/TwigCustomPagination.php',
	'TwigContentElement'               => 'system/modules/twig/TwigContentElement.php',
	'TwigBackendModule'                => 'system/modules/twig/TwigBackendModule.php',
	'TwigPagination'                   => 'system/modules/twig/TwigPagination.php',
	'TwigFrontendTemplate'             => 'system/modules/twig/TwigFrontendTemplate.php',
	'ContaoTwig'                       => 'system/modules/twig/ContaoTwig.php',
	'TwigHybrid'                       => 'system/modules/twig/TwigHybrid.php',
));
