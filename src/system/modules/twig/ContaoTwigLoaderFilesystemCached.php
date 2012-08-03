<?php

class ContaoTwigLoaderFilesystemCached extends Twig_Loader_Filesystem
{
	/**
	 * @var ApcCache
	 */
	protected $cache;

	public function __construct($paths)
	{
		parent::__construct($paths);

		$this->cache = FileCache::getInstance('twig');
	}

    /**
     * Adds a path where templates are stored.
     *
     * @param string $path A path where to look for templates
     */
    public function addPath($path)
    {
        if (!is_dir($path)) {
            throw new Twig_Error_Loader(sprintf('The "%s" directory does not exist.', $path));
        }

        $this->paths[] = rtrim($path, '/\\');
    }
}