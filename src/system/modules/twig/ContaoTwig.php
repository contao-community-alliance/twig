<?php

class ContaoTwig
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
	 * @var Twig_Loader_Filesystem
	 */
	protected $loaderFilesystem;

	/**
	 * @var Twig_Loader_Chain
	 */
	protected $loader;

	/**
	 * @var Twig_Environment
	 */
	protected $environment;

	/**
	 * Create the new twig contao environment
	 */
	protected function __construct()
	{
		$arrTemplatePaths = array();

		// Add the layout templates directory
		if (TL_MODE == 'FE') {
			global $objPage;
			$strTemplateGroup = str_replace(array('../', 'templates/'), '', $objPage->templateGroup);

			if ($strTemplateGroup != '') {
				$arrTemplatePaths[] = TL_ROOT . '/templates/' . $strTemplateGroup;
			}
		}

		// Add the global templates directory
		$arrTemplatePaths[] = TL_ROOT . '/templates';

		// Add all modules templates directories
		foreach (Config::getInstance()->getActiveModules() as $strModule) {
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
				'cache' => TL_ROOT . '/system/cache',
				'debug' => $GLOBALS['TL_CONFIG']['debugMode']
			)
		);

		// Add some globals
		$this->environment->addGlobal('_lang', new ContaoTwigLang());

		// Add some filters
		$this->environment->addFilter('deserialize', new Twig_Filter_Function('deserialize'));
		$this->environment->addFilter('standardize', new Twig_Filter_Function('standardize'));
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
}