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
 * Class ContaoTwig
 *
 * Set up the twig environment and provide the template loaders.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 */
class ContaoTwig
    extends System
{
    /**
     * @var ContaoTwig
     */
    protected static $objInstance = null;

    /**
     * Return the instance of ContaoTwig.
     *
     * @static
     * @return ContaoTwig|Twig_Environment
     */
    public static function getInstance()
    {
        if (self::$objInstance === null) {
            self::$objInstance = new self;
        }
        return self::$objInstance;
    }

    /**
     * The filesystem template loader.
     *
     * @var Twig_Loader_Filesystem
     */
    protected $loaderFilesystem;

    /**
     * The loader chain that is used by the environment.
     *
     * @var Twig_Loader_Chain
     */
    protected $loader;

    /**
     * The Twig environment.
     *
     * @var Twig_Environment
     */
    protected $environment;

    /**
     * Create the new twig contao environment
     */
    protected function __construct()
    {
        $arrTemplatePaths = array();

        $blnDebug = $GLOBALS['TL_CONFIG']['debugMode'] || $GLOBALS['TL_CONFIG']['twigDebugMode'];

        // Make sure the cache directory exists
        if (version_compare(VERSION,
                            '2',
                            '<=') && !is_dir(TL_ROOT . '/system/cache')
        ) {
            Files::getInstance()
                ->mkdir('system/cache');
        }
        if (!is_dir(TL_ROOT . '/system/cache/twig')) {
            Files::getInstance()
                ->mkdir('system/cache/twig');
        }

        // Add the layout templates directory
        if (TL_MODE == 'FE') {
            global $objPage;
            $strTemplateGroup = str_replace(array('../', 'templates/'),
                                            '',
                                            $objPage->templateGroup);

            if ($strTemplateGroup != '') {
                $arrTemplatePaths[] = TL_ROOT . '/templates/' . $strTemplateGroup;
            }
        }

        // Add the global templates directory
        $arrTemplatePaths[] = TL_ROOT . '/templates';

        // Add all modules templates directories
        foreach (Config::getInstance()
                     ->getActiveModules() as $strModule) {
            $strPath = TL_ROOT . '/system/modules/' . $strModule . '/templates';

            if (is_dir($strPath)) {
                $arrTemplatePaths[] = $strPath;
            }
        }

        // Create the default filesystem loader
        $this->loaderFilesystem = new ContaoTwigLoaderFilesystemCached($arrTemplatePaths);

        // Create the effective chain loader
        $this->loader = new Twig_Loader_Chain();

        // Register the default filesystem loader
        $this->loader->addLoader($this->loaderFilesystem);

        // Create the environment
        $this->environment = new Twig_Environment(
            $this->loader,
            array(
                 'cache' => TL_ROOT . '/system/cache/twig',
                 'debug' => $blnDebug
            )
        );

        // Add debug extension
        if ($blnDebug || $GLOBALS['TL_CONFIG']['twigDebugExtension']) {
            $this->environment->addExtension(new Twig_Extension_Debug());
        }

        // Add some globals
        $this->environment->addGlobal('REQUEST_TOKEN',
                                      REQUEST_TOKEN);
        $this->environment->addGlobal('_lang',
                                      new ContaoTwigGlobalAccessObject('TL_LANG'));
        $this->environment->addGlobal('_dca',
                                      new ContaoTwigGlobalAccessObject('TL_DCA'));
        $this->environment->addGlobal('_config',
                                      new ContaoTwigGlobalAccessObject('TL_CONFIG'));
        $this->environment->addGlobal('_db',
                                      Database::getInstance());

        // Add some filters
        $this->environment->addFilter('deserialize',
                                      new Twig_Filter_Function('deserialize'));
        $this->environment->addFilter('standardize',
                                      new Twig_Filter_Function('standardize'));
        $this->environment->addFilter('dateFormat',
                                      new Twig_Filter_Function('ContaoTwig::parseDateFilter'));
        $this->environment->addFilter('datimFormat',
                                      new Twig_Filter_Function('ContaoTwig::parseDatimFilter'));

        // Add database access filters
        $this->environment->addFilter('prepare',
                                      new Twig_Filter_Function('ContaoTwig::prepareFilter'));
        $this->environment->addFilter('set',
                                      new Twig_Filter_Function('ContaoTwig::setFilter'));
        $this->environment->addFilter('execute',
                                      new Twig_Filter_Function('ContaoTwig::executeFilter'));
        $this->environment->addFilter('query',
                                      new Twig_Filter_Function('ContaoTwig::queryFilter'));

        // HOOK: custom twig initialisation
        if (isset($GLOBALS['TL_HOOKS']['initializeTwig']) && is_array($GLOBALS['TL_HOOKS']['initializeTwig'])) {
            foreach ($GLOBALS['TL_HOOKS']['initializeTwig'] as $callback) {
                $this->import($callback[0]);
                $this->$callback[0]->$callback[1]($this);
            }
        }
    }

    /**
     * Return the default filesystem loader.
     *
     * @return Twig_Loader_Filesystem
     */
    public function getLoaderFilesystem()
    {
        return $this->loaderFilesystem;
    }

    /**
     * Return the chain loader, that is registered to the environment.
     *
     * @return Twig_Loader_Chain
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Return the twig environment.
     *
     * @return Twig_Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Parse the timestamp with the default date format.
     *
     * @static
     *
     * @param int $timestamp
     *
     * @return string
     */
    public static function parseDateFilter($timestamp)
    {
        return self::getInstance()
            ->parseDate($GLOBALS['TL_CONFIG']['dateFormat'],
                        $timestamp);
    }

    /**
     * Parse the timestamp with the default date and time format.
     *
     * @static
     *
     * @param int $timestamp
     *
     * @return string
     */
    public static function parseDatimFilter($timestamp)
    {
        return self::getInstance()
            ->parseDate($GLOBALS['TL_CONFIG']['datimFormat'],
                        $timestamp);
    }

    /**
     * Prepare a database statement.
     *
     * @static
     *
     * @param string $sql
     *
     * @return Database_Statement
     */
    public static function prepareFilter($sql)
    {
        return Database::getInstance()
            ->prepare($sql);
    }

    /**
     * Set database statement update arguments.
     *
     * @static
     *
     * @param Database_Statement $statement
     * @param array              $arguments
     *
     * @return Database_Statement
     */
    public static function setFilter(Database_Statement $statement,
                                     array $arguments)
    {
        return $statement->set($arguments);
    }

    /**
     * Execute a database statement.
     *
     * @static
     *
     * @param string|Database_Statement $statement
     * @param array                     $arguments
     *
     * @return Database_Result
     */
    public static function executeFilter($statement,
                                         array $arguments = array())
    {
        if ($statement instanceof Database_Statement) {
            /** @var Database_Statement $statement */
            return $statement
                ->execute($arguments)
                ->fetchAllAssoc();
        }

        else {
            return Database::getInstance()
                ->execute($statement)
                ->fetchAllAssoc();
        }
    }

    /**
     * Execute a database query.
     *
     * @static
     *
     * @param string $statement
     *
     * @return Database_Result
     */
    public static function queryFilter($statement)
    {
        return Database::getInstance()
            ->query($statement)
            ->fetchAllAssoc();
    }
}
