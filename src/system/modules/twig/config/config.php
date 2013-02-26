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

require_once(TL_ROOT . '/system/modules/twig/vendor/twig/lib/Twig/Autoloader.php');

// Contao 3
if (version_compare(VERSION,
                    '3',
                    '>=')
) {
    /**
     * Autoloader
     */
    Twig_Autoloader::register();

    /**
     * Purge jobs
     */
    $GLOBALS['TL_PURGE']['folders']['twig'] = array
    (
        'callback' => array('PurgeTwigCache', 'purge'),
        'affected' => array('system/cache/twig')
    );

    if (Input::get('do') == 'maintenance') {
        /**
         * Scan the twig directory structure and add affected paths to TL_PURGE.
         *
         * @param $strDirectory
         */
        function scanTwigCacheDirectories($strDirectory) {
            $blnHasFiles = false;
            $arrFiles = scan(TL_ROOT . '/' . $strDirectory);

            // Walk over the children
            foreach ($arrFiles as $strPath) {
                $strPath = $strDirectory . '/' . $strPath;

                // Add directory and scan it
                if (is_dir(TL_ROOT . '/' . $strPath)) {
                    $GLOBALS['TL_PURGE']['folders']['twig']['affected'][] = $strPath;
                    scanTwigCacheDirectories($strPath);
                }

                // Remember that directory contains files
                else {
                    $blnHasFiles = true;
                }
            }

            // Remove directories that only contains structure
            if (!$blnHasFiles) {
                $intPos = array_search($strDirectory, $GLOBALS['TL_PURGE']['folders']['twig']['affected']);
                unset($GLOBALS['TL_PURGE']['folders']['twig']['affected'][$intPos]);
            }
        }
        scanTwigCacheDirectories('system/cache/twig');
    }
}

// Contao 2
else {
    // Twig_Autoloader::register();
    ini_set('unserialize_callback_func',
            'spl_autoload_call');
	spl_autoload_unregister('__autoload');
    Twig_Autoloader::register();
    spl_autoload_register('__autoload');

    /**
     * Maintenance
     */
    $GLOBALS['TL_MAINTENANCE'][] = 'PurgeTwigCache';
}
