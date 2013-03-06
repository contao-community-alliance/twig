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
 * Class TwigSimpleHybrid
 *
 * A specialised implementation of TwigHybrid that does not need a third data table.
 * The SimpleHybrid use the given element itself (content element or module) as data source.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
abstract class TwigSimpleHybrid
	extends TwigHybrid
{
	public function __construct(Database_Result $objElement)
	{
		parent::__construct($objElement);

		$this->arrData = $objElement->row();

		// Get space and CSS ID from the parent element (!)
		$this->space = deserialize($objElement->space);
		$this->cssID = deserialize(
			$objElement->cssID,
			true
		);

		$this->typePrefix = 'mod_';
		$this->strKey     = $objElement->type;

		$arrHeadline    = deserialize($objElement->headline);
		$this->headline = is_array($arrHeadline)
			? $arrHeadline['value']
			: $arrHeadline;
		$this->hl       = is_array($arrHeadline)
			? $arrHeadline['unit']
			: 'h1';
	}
}