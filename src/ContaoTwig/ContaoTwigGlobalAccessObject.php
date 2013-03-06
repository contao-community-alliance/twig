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
	 * @param string $k
	 *
	 * @return mixed
	 */
	public function __get($k)
	{
		return $GLOBALS[$this->var][$k];
	}

	/**
	 * @param string $k
	 *
	 * @return bool
	 */
	public function __isset($k)
	{
		return isset($GLOBALS[$this->var][$k]);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return "\$GLOBALS['{$this->var}']";
	}
}
