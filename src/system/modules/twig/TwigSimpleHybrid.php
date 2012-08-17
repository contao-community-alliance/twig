<?php

abstract class TwigSimpleHybrid extends TwigHybrid
{
	public function __construct(Database_Result $objElement)
	{
		parent::__construct($objElement);

		$this->arrData = $objElement->row();

		// Get space and CSS ID from the parent element (!)
		$this->space = deserialize($objElement->space);
		$this->cssID = deserialize($objElement->cssID, true);

		$this->typePrefix = $objElement->typePrefix;

		$arrHeadline = deserialize($objElement->headline);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
	}
}