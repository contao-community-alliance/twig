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
 * Class ContaoTwigEnvironmentAccessObject
 *
 * Provide access to environment.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigEnvironmentAccessObject
// @codingStandardsIgnoreEnd
{
    /**
     * Retrieve a value from the Environment.
     *
     * @param string $key The key to retrieve.
     *
     * @return mixed
     */
    public function __get($key)
    {
        return \Environment::get($key);
    }

    /**
     * Check if a value has been set.
     *
     * @param string $key The key to check.
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __isset($key)
    {
        return true;
    }

    /**
     * Get a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return '{environment}';
    }
}
