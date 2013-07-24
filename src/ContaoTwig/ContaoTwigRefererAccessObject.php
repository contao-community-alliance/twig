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
 * Provide access to the Contao referer.
 *
 * @package ContaoTwig
 * @author  Oliver Hoff <oliver@hofff.com>
 */
class ContaoTwigRefererAccessObject extends System
{

	public function __construct()
	{
		parent::__construct();
	}

	public function __toString()
	{
		return $this->current();
	}

	public function current()
	{
		if (version_compare(VERSION, '3', '>=')) {
			return System::getReferer();
		} else {
			return $this->getReferer();
		}
	}
}
