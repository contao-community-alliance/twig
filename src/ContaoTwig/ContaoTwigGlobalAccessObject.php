<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package   ContaoTwig
 * @author    Tristan Lins <tristan.lins@bit3.de>
 * @author    Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @copyright 2012-2015 Tristan Lins.
 * @copyright 2015-2016 Contao Community Alliance
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 * @link      https://github.com/bit3/contao-twig SCM
 * @link      http://de.contaowiki.org/Twig Wiki
 */

/**
 * Provide access to global variables by reference..
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigGlobalAccessObject
// @codingStandardsIgnoreEnd
{
    /**
     * The global root key name.
     *
     * @var string
     */
    protected $var;

    /**
     * Create a new instance.
     *
     * @param string $var The root key to work on in the globals array.
     */
    public function __construct($var)
    {
        $this->var = $var;
    }

    /**
     * Retrieve a value from the globals array.
     *
     * @param string $key The sub key to obtain.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function __get($key)
    {
        return $GLOBALS[$this->var][$key];
    }

    /**
     * Check if a value has been set.
     *
     * @param string $key The sub key to check.
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function __isset($key)
    {
        return isset($GLOBALS[$this->var][$key]);
    }

    /**
     * Retrieve as string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('$GLOBALS[\'%s\']', $this->var);
    }
}
