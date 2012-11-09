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
 * Class PurgeTwigCache
 *
 * Maintenance module to purge the twig cache.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 */
class PurgeTwigCache
    extends Backend
    implements executable
{
    /**
     * Singleton instance.
     *
     * @var PurgeTwigCache
     */
    protected static $objInstance = null;

    /**
     * Get singleton instance.
     *
     * @return PurgeTwigCache
     */
    public static function getInstance()
    {
        if (self::$objInstance === null) {
            self::$objInstance = new PurgeTwigCache();
        }
        return self::$objInstance;
    }

    protected function __construct()
    {
        parent::__construct();

        $this->import('Files');
    }

    /**
     * Return true if the module is active
     * @return boolean
     */
    public function isActive()
    {
        return (bool) ($this->Input->post('FORM_SUBMIT') == 'tl_purge_twig_cache');
    }

    /**
     * Generate the module
     * @return string
     */
    public function run()
    {
        $objTemplate           = new TwigBackendTemplate('be_purge_images');
        $objTemplate->isActive = $this->isActive();

        // Confirmation message
        if ($_SESSION['CLEAR_TWIG_CACHE_CONFIRM'] != '') {
            $objTemplate->cacheMessage            = $_SESSION['CLEAR_TWIG_CACHE_CONFIRM'];
            $_SESSION['CLEAR_TWIG_CACHE_CONFIRM'] = '';
        }

        // Purge the resources
        if ($this->isActive()) {
            $this->import('Files');

            $intCount = $this->purge();

            $_SESSION['CLEAR_TWIG_CACHE_CONFIRM'] = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['purgedTwigCache'],
                                                            $intCount);

            $this->reload();
        }

        // count existing files
        $count    = 0;
        $size     = 0;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(TL_ROOT . '/system/cache/twig'),
                                                  RecursiveIteratorIterator::CHILD_FIRST);
        /** @var SplFileInfo $path */
        foreach ($iterator as $path) {
            if ($path->isFile() && $path->getFilename() != '.keep') {
                $count++;
                $size += $path->getSize();
            }
        }

        $objTemplate->count  = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['twigCacheCount'],
                                       $count,
                                       $this->getReadableSize($size));
        $objTemplate->action = ampersand($this->Environment->request);

        return $objTemplate->parse();
    }

    /**
     * Purge the twig cache directory
     */
    public function purge()
    {
        $intCount = 0;

        // remove files recursive
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(TL_ROOT . '/system/cache/twig'),
                                                  RecursiveIteratorIterator::CHILD_FIRST);

        /** @var SplFileInfo $path */
        foreach ($iterator as $path) {
            if ($path->isFile() && $path->getFilename() != '.keep') {
                $this->Files->delete(substr($path->getRealPath(),
                                            strlen(TL_ROOT) + 1));
                $intCount++;
            }
        }

        // Add log entry
        $this->log('Purged twig cache directory',
                   'PurgeTwigCache purge',
                   TL_CRON);

        return $intCount;
    }
}