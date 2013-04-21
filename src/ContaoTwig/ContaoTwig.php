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

/**
 * Class ContaoTwig
 *
 * Set up the twig environment and provide the template loaders.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwig
	extends Controller
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
	 */
	protected function __construct()
	{
		$arrTemplatePaths = array();

		$blnDebug = $GLOBALS['TL_CONFIG']['debugMode'] || $GLOBALS['TL_CONFIG']['twigDebugMode'];

		// Make sure the cache directory exists
		if (version_compare(
			VERSION,
			'2',
			'<='
		) && !is_dir(TL_ROOT . '/system/cache')
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
			$strTemplateGroup = str_replace(
				array('../', 'templates/'),
				'',
				$objPage->templateGroup
			);

			if ($strTemplateGroup != '') {
				$arrTemplatePaths[] = TL_ROOT . '/templates/' . $strTemplateGroup;
			}
		}

		// Add the global templates directory
		$arrTemplatePaths[] = TL_ROOT . '/templates';

		// Add all modules templates directories
		foreach (
			Config::getInstance()
				->getActiveModules() as $strModule
		) {
			$strPath = TL_ROOT . '/system/modules/' . $strModule . '/templates';

			if (is_dir($strPath)) {
				$arrTemplatePaths[] = $strPath;
			}
		}

		// Create the default array loader
		$this->loaderArray = new Twig_Loader_Array(array());

		// Create the default filesystem loader
		$this->loaderFilesystem = new Twig_Loader_Filesystem($arrTemplatePaths);

		// Create the effective chain loader
		$this->loader = new Twig_Loader_Chain();

		// Register the default filesystem loaders
		$this->loader->addLoader($this->loaderArray);
		$this->loader->addLoader($this->loaderFilesystem);

		// Create the environment
		$this->environment = new Twig_Environment(
			$this->loader,
			array(
				'cache'      => TL_ROOT . '/system/cache/twig',
				'debug'      => $blnDebug,
				'autoescape' => false
			)
		);

		// set default formats
		$this->environment->getExtension('core')->setNumberFormat(2, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']);

		// set default date format and timezone
		$this->environment->getExtension('core')->setDateFormat($GLOBALS['TL_CONFIG']['datimFormat']);
		$this->environment->getExtension('core')->setTimezone('Europe/Paris');

		// Add debug extension
		if ($blnDebug || $GLOBALS['TL_CONFIG']['twigDebugExtension']) {
			$this->environment->addExtension(new Twig_Extension_Debug());
		}

		$this->environment->addExtension(new ContaoTwigExtension());

		// HOOK: custom twig initialisation
		if (isset($GLOBALS['TL_HOOKS']['initializeTwig']) && is_array($GLOBALS['TL_HOOKS']['initializeTwig'])) {
			foreach ($GLOBALS['TL_HOOKS']['initializeTwig'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this);
			}
		}
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
}
