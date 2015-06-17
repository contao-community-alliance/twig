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
 * Class ContaoTwigGlobalAccessObject
 *
 * Provide access to global variables by reference..
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwigGlobalAccessObject
{
    /**
     * The global variable name.
     *
     * @var string
     */
    protected $var;

    /**
     * @param string $var
     */
    public function __construct($var)
    {
        $this->var = $var;
    }

    /**
     * @param string $key
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
     * @param string $key
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
     * @return string
     */
    public function __toString()
    {
        return "\$GLOBALS['{$this->var}']";
    }
}
