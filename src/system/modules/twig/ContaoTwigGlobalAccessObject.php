<?php

class ContaoTwigGlobalAccessObject
{
	/**
	 * @var string
	 */
	protected $var;

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
