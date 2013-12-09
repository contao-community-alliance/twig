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
 * Class ContaoTwigOptionsBuilder
 */
class ContaoTwigOptionsBuilder
{
	static public function getTemplateOptions($templatePrefix = '', ContaoTwig $twig = null)
	{
		if (!$twig) {
			$twig = ContaoTwig::getInstance();
		}

		$options = array();
		$paths = $twig->getLoaderFilesystem()->getPaths();

		foreach ($paths as $path) {
			static::collectTemplateOptions($templatePrefix, $path, $options);
		}

		return $options;
	}

	static protected function collectTemplateOptions($templatePrefix, $path, &$options)
	{
		$relativePath = str_replace(TL_ROOT . '/', '', $path);

		$iterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS);
		$iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::LEAVES_ONLY);

		$options[$relativePath] = array();

		/** @var SplFileInfo $file */
		foreach ($iterator as $file) {
			if (
				$file->isFile() &&
				preg_match('~\.twig$~', $file->getFilename()) &&
				(
					!$templatePrefix ||
					strpos($file->getFilename(), $templatePrefix) === 0
				)
			) {
				$templateName = str_replace($path . '/', '', $file->getPathname());

				$options[$relativePath][$templateName] = $templateName;
			}
		}

		if (empty($options[$relativePath])) {
			unset($options[$relativePath]);
		}
		else {
			uksort($options, 'strnatcasecmp');
			uksort($options[$relativePath], 'strnatcasecmp');
		}
	}
}
