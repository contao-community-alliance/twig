<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/bit3/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class ContaoTwig
 *
 * Set up the twig environment and provide the template loaders.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwig extends Controller
{
    /**
     * @var ContaoTwig
     */
    protected static $objInstance = null;

    /**
     * Return the instance of ContaoTwig.
     *
     * @param ContaoTwigConfig $config
     *
     * @return ContaoTwig|Twig_Environment
     */
    public static function getInstance(ContaoTwigConfig $config = null)
    {
        if (!$config) {
            $config = new ContaoTwigConfig();
        }
        $key = (string)$config;
        if (self::$objInstance[$key] === null) {
            self::$objInstance[$key] = new self($config);
        }

        return self::$objInstance[$key];
    }

    /**
     * The array template loader.
     *
     * @var Twig_Loader_Array
     */
    protected $loaderArray;

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
     *
     * @param ContaoTwigConfig $config
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function __construct(ContaoTwigConfig $config)
    {
        $blnDebug = $config->isAllowDebugMode()
            && ($GLOBALS['TL_CONFIG']['debugMode'] || $GLOBALS['TL_CONFIG']['twigDebugMode']);

        $this->ensureDirectoryExist();

        // Create the effective chain loader
        $this->loader = new Twig_Loader_Chain();

        $this->enableArrayLoader($config);
        $this->enableFilesystemLoader($config);

        $this->createEnvironment($config, $blnDebug);
        $this->addDefaultFormats($config);

        // Add debug extension
        if ($config->isAllowDebugMode() && ($blnDebug || $GLOBALS['TL_CONFIG']['twigDebugExtension'])) {
            $this->environment->addExtension(new Twig_Extension_Debug());
        }

        if ($config->isEnableContaoExtension()) {
            $this->environment->addExtension(new ContaoTwigExtension());
        }

        $this->callInitializationHooks($config);

        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];
        $eventDispatcher->dispatch('contao-twig.init', new ContaoTwigInitializeEvent($this));
    }

    /**
     * Return the default string loader.
     *
     * @return \Twig_Loader_Array
     */
    public function getLoaderArray()
    {
        return $this->loaderArray;
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
     * Ensure that the template directories exist.
     *
     * @return void
     */
    private function ensureDirectoryExist()
    {
        if (!is_dir(TL_ROOT . '/system/cache/twig')) {
            if (!is_dir(TL_ROOT . '/system/cache')) {
                Files::getInstance()
                    ->mkdir('system/cache');
            }

            Files::getInstance()
                ->mkdir('system/cache/twig');
        }
    }

    private function getTemplatePathes(ContaoTwigConfig $config)
    {
        $arrTemplatePaths = array();
        // Add the layout templates directory
        if ($config->isEnableThemeTemplatesLoader() && TL_MODE == 'FE') {
            global $objPage;
            $strTemplateGroup = str_replace(
                array(
                    '../',
                    'templates/'
                ),
                '',
                $objPage->templateGroup
            );

            if ($strTemplateGroup != '') {
                $arrTemplatePaths[] = TL_ROOT . '/templates/' . $strTemplateGroup;
            }
        } else {
            if (TL_MODE == 'BE') {
                $themeCollection = \ThemeModel::findAll();

                if ($themeCollection) {
                    while ($themeCollection->next()) {
                        if ($themeCollection->templates) {
                            $arrTemplatePaths[] = TL_ROOT . '/' . $themeCollection->templates;
                        }
                    }
                }
            }
        }

        // Add the global templates directory
        if ($config->isEnableGlobalTemplatesLoader()) {
            $arrTemplatePaths[] = TL_ROOT . '/templates';
        }
        $this->addModuleTemplatePathes($config, $arrTemplatePaths);

        return $arrTemplatePaths;
    }

    /**
     *
     * @param ContaoTwigConfig $config
     * @param                  $arrTemplatePaths
     *
     * @return void
     */
    private function addModuleTemplatePathes(ContaoTwigConfig $config, &$arrTemplatePaths)
    {
        if ($config->isEnableModuleTemplatesLoader()) {
            foreach (
                Config::getInstance()
                    ->getActiveModules() as $strModule
            ) {
                $strPath = TL_ROOT . '/system/modules/' . $strModule . '/templates';

                if (is_dir($strPath)) {
                    $arrTemplatePaths[] = $strPath;
                }
            }
        }
    }

    /**
     *
     * @param ContaoTwigConfig $config
     *
     * @return void
     */
    private function enableArrayLoader(ContaoTwigConfig $config)
    {
// Create the default array loader
        if ($config->isEnableArrayLoader()) {
            $this->loaderArray = new Twig_Loader_Array(array());
            $this->loader->addLoader($this->loaderArray);
        }
    }

    /**
     *
     * @param ContaoTwigConfig $config
     *
     * @return void
     */
    private function enableFilesystemLoader(ContaoTwigConfig $config)
    {
// Create the default filesystem loader
        if ($config->isEnableFilesystemLoader()) {
            $this->loaderFilesystem = new Twig_Loader_Filesystem($this->getTemplatePathes($config));
            $this->loader->addLoader($this->loaderFilesystem);
        }
    }

    /**
     *
     * @param ContaoTwigConfig $config
     * @param                  $blnDebug
     *
     * @return void
     */
    private function createEnvironment(ContaoTwigConfig $config, $blnDebug)
    {
// Create the environment
        $this->environment = new Twig_Environment(
            $this->loader,
            array(
                'cache'      => TL_ROOT . '/system/cache/twig',
                'debug'      => $blnDebug,
                'autoescape' => $config->isEnableAutoescape()
            )
        );
    }

    /**
     *
     * @param ContaoTwigConfig $config
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function addDefaultFormats(ContaoTwigConfig $config)
    {
        if ($config->isSetNumberFormat()) {
            $this->environment->getExtension('core')->setNumberFormat(
                2,
                $GLOBALS['TL_LANG']['MSC']['decimalSeparator'],
                $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']
            );
        }

        // set default date format and timezone
        if ($config->isSetDateFormat()) {
            $this->environment->getExtension('core')->setDateFormat($GLOBALS['TL_CONFIG']['datimFormat']);
        }
        if ($config->isSetTimeZone()) {
            $this->environment->getExtension('core')->setTimezone($GLOBALS['TL_CONFIG']['timeZone']);
        }
    }

    /**
     *
     * @param ContaoTwigConfig $config
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function callInitializationHooks(ContaoTwigConfig $config)
    {
        // HOOK: custom twig initialisation
        if ($config->isCallInitializationHook() && isset($GLOBALS['TL_HOOKS']['initializeTwig']) && is_array(
                $GLOBALS['TL_HOOKS']['initializeTwig']
            )
        ) {
            foreach ($GLOBALS['TL_HOOKS']['initializeTwig'] as $callback) {
                $this->import($callback[0]);
                $this->$callback[0]->$callback[1]($this);
            }
        }
    }
}
