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
 * Class ContaoTwigOptionsBuilder.
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigOptionsBuilder
// @codingStandardsIgnoreEnd
{
    /**
     * Retrieve the template options.
     *
     * @param string          $templatePrefix The template prefix to use.
     *
     * @param ContaoTwig|null $twig           The twig instance to use.
     *
     * @return array
     */
    public static function getTemplateOptions($templatePrefix = '', ContaoTwig $twig = null)
    {
        if (!$twig) {
            $twig = ContaoTwig::getInstance();
        }

        $options = array();
        $paths   = $twig->getLoaderFilesystem()->getPaths();

        foreach ($paths as $path) {
            static::collectTemplateOptions($templatePrefix, $path, $options);
        }

        return $options;
    }

    /**
     * Collect the template options.
     *
     * @param string $templatePrefix The template prefix.
     *
     * @param string $path           The template path.
     *
     * @param array  $options        The options array to populate.
     *
     * @return void
     */
    protected static function collectTemplateOptions($templatePrefix, $path, &$options)
    {
        $relativePath = str_replace(TL_ROOT . '/', '', $path);

        $iterator = new RecursiveDirectoryIterator(
            $path,
            (FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS)
        );
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
        } else {
            uksort($options, 'strnatcasecmp');
            uksort($options[$relativePath], 'strnatcasecmp');
        }
    }
}
