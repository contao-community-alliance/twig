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

// Contao 3
if (version_compare(
    VERSION,
    '3',
    '>='
)
) {
    /**
     * Purge jobs
     */
    $GLOBALS['TL_PURGE']['folders']['twig'] = array
    (
        'callback' => array(
            'PurgeTwigCache',
            'purge'
        ),
        'affected' => array('system/cache/twig')
    );

    if (Input::get('do') == 'maintenance') {
        /**
         * Scan the twig directory structure and add affected paths to TL_PURGE.
         *
         * @param $strDirectory
         */
        function scanTwigCacheDirectories($strDirectory)
        {
            $blnHasFiles = false;
            $arrFiles    = scan(TL_ROOT . '/' . $strDirectory);

            // Walk over the children
            foreach ($arrFiles as $strPath) {
                $strPath = $strDirectory . '/' . $strPath;

                // Add directory and scan it
                if (is_dir(TL_ROOT . '/' . $strPath)) {
                    $GLOBALS['TL_PURGE']['folders']['twig']['affected'][] = $strPath;
                    scanTwigCacheDirectories($strPath);
                } // Remember that directory contains files
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
} // Contao 2
else {
    /**
     * Maintenance
     */
    $GLOBALS['TL_MAINTENANCE'][] = 'PurgeTwigCache';
}

if (!file_exists(TL_ROOT . '/system/cache/twig')) {
    mkdir(TL_ROOT . '/system/cache/twig', 0777, true);
}

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['texts']['twig'] = 'ContentTwig';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['miscellaneous']['twig'] = 'ModuleTwig';
