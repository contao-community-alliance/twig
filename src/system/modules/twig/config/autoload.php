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
    // Core classes
    'ContaoTwig'                       => 'system/modules/twig/ContaoTwig.php',
    'ContaoTwigCache'                  => 'system/modules/twig/ContaoTwigCache.php',
    'ContaoTwigGlobalAccessObject'     => 'system/modules/twig/ContaoTwigGlobalAccessObject.php',
    'ContaoTwigLoaderFilesystemCached' => 'system/modules/twig/ContaoTwigLoaderFilesystemCached.php',
    'TwigHelper'                       => 'system/modules/twig/TwigHelper.php',

    // Backend
    'PurgeTwigCache'                   => 'system/modules/twig/PurgeTwigCache.php',

    // Template base classes
    'TwigBackendTemplate'              => 'system/modules/twig/TwigBackendTemplate.php',
    'TwigFrontendTemplate'             => 'system/modules/twig/TwigFrontendTemplate.php',

    // Module base classes
    'TwigModule'                       => 'system/modules/twig/TwigModule.php',
    'TwigBackendModule'                => 'system/modules/twig/TwigBackendModule.php',
    'TwigPagination'                   => 'system/modules/twig/TwigPagination.php',
    'TwigCustomPagination'             => 'system/modules/twig/TwigCustomPagination.php',
    'TwigHybrid'                       => 'system/modules/twig/TwigHybrid.php',
    'TwigSimpleHybrid'                 => 'system/modules/twig/TwigSimpleHybrid.php',

    // Content element base classes
    'TwigContentElement'               => 'system/modules/twig/TwigContentElement.php',
));
