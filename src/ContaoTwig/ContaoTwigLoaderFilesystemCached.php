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
 * Class ContaoTwigLoaderFilesystemCached
 *
 * A Twig_Loader_Filesystem implementation that use ContaoTwigCache for caching.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwigLoaderFilesystemCached
	extends Twig_Loader_Filesystem
{
	/**
	 * @var ContaoTwigCache
	 */
	protected $cache;

	public function __construct($paths)
	{
		parent::__construct($paths);

		$this->cache = ContaoTwigCache::getInstance('twig');
	}

	/**
	 * Adds a path where templates are stored.
	 *
	 * @param string $path A path where to look for templates
	 */
	public function addPath($path)
	{
		if (!is_dir($path)) {
			throw new Twig_Error_Loader(sprintf(
				'The "%s" directory does not exist.',
				$path
			));
		}

		$this->paths[] = rtrim(
			$path,
			'/\\'
		);
	}
}