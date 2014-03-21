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
 * Class TwigHelper
 *
 * A helper class with some functions.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class TwigHelper
{
	/**
	 * Singleton instance.
	 *
	 * @var TwigHelper
	 */
	protected static $objInstance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @static
	 * @return TwigHelper
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null) {
			self::$objInstance = new TwigHelper();
		}
		return self::$objInstance;
	}

	/**
	 * Return all template files of a particular group as array
	 *
	 * @param string
	 * @param integer
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function getTemplateGroup($prefix, $themeId = 0)
	{
		$folders = array();

		// Add the templates root directory
		$folders['/templates'] = TL_ROOT . '/templates';

		// Add the theme templates folder
		if ($themeId > 0) {
			$resultSet = Database::getInstance()
				->prepare("SELECT templates FROM tl_theme WHERE id=?")
				->limit(1)
				->execute($themeId);

			if ($resultSet->numRows > 0 && $resultSet->templates != '') {
				$folders[$resultSet->title] = TL_ROOT . '/' . $resultSet->templates;
			}
		}

		// Add the module templates folders if they exist
		$activeModules = Config::getInstance()->getActiveModules();
		foreach ($activeModules as $module) {
			$folder = TL_ROOT . '/system/modules/' . $module . '/templates';

			if (is_dir($folder)) {
				$folders['system/modules/' . $module] = $folder;
			}
		}

		return static::getTemplateGroupInFolders($prefix, $folders);
	}

	/**
	 * Return all template files of a particular group as array
	 *
	 * @param string
	 * @param integer
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function getTemplateGroupInFolders($prefix, $folders)
	{
		$templates = array();

		// Find all matching templates
		foreach ($folders as $sourceName => $folder) {
			$iterator = new RecursiveDirectoryIterator(
				$folder,
				RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::UNIX_PATHS
			);
			$iterator = new RecursiveIteratorIterator(
				$iterator,
				RecursiveIteratorIterator::LEAVES_ONLY
			);
			$iterator = new RegexIterator(
				$iterator,
				'~(^|/)' . preg_quote($prefix, '~') . '.*\.twig$~i'
			);
			/** @var RecursiveDirectoryIterator|RecursiveIteratorIterator|RegexIterator $iterator */
			$iterator->next();

			while ($iterator->accept()) {
				$templateName = $iterator->getSubPathname();
				$templateName = preg_replace('#\.[^\.]+\.twig$#', '', $templateName);

				$templates[$sourceName][$templateName] = $templateName;
				uksort($templates[$sourceName], 'strnatcasecmp');

				$iterator->next();
			}
		}

		uksort($templates, 'strnatcasecmp');

		return $templates;
	}
}
