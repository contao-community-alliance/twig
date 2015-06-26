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
use Database\Result;
use Model\Collection;

/**
 * Class TwigSimpleHybrid
 *
 * A specialised implementation of TwigHybrid that does not need a third data table.
 * The SimpleHybrid use the given element itself (content element or module) as data source.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
abstract class TwigSimpleHybrid extends TwigHybrid
// @codingStandardsIgnoreEnd
{
    /**
     * Create a new instance.
     *
     * @param Result|Collection $objElement The values from the database for the element.
     */
    public function __construct($objElement)
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
