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
 * Class TwigHybrid
 *
 * A Hybrid implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
abstract class TwigHybrid extends Hybrid
// @codingStandardsIgnoreEnd
{
    /**
     * The template instance.
     *
     * @var TwigFrontendTemplate
     *
     * @codingStandardsIgnoreStart
     */
    protected $Template;
    // @codingStandardsIgnoreEnd

    /**
     * Parse the template.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function generate()
    {
        if ($this->arrData['space'][0] != '') {
            $this->arrStyle[] = 'margin-top:' . $this->arrData['space'][0] . 'px;';
        }

        if ($this->arrData['space'][1] != '') {
            $this->arrStyle[] = 'margin-bottom:' . $this->arrData['space'][1] . 'px;';
        }

        $this->Template = new TwigFrontendTemplate($this->strTemplate);
        $this->Template->setData($this->arrData);

        $this->compile();

        $this->Template->inColumn = $this->strColumn;
        $this->Template->style    = !empty($this->arrStyle)
            ? implode(
                ' ',
                $this->arrStyle
            )
            : '';
        $this->Template->cssID    = ($this->cssID[0] != '')
            ? ' id="' . $this->cssID[0] . '"'
            : '';
        $this->Template->class    = trim($this->typePrefix . $this->strKey . ' ' . $this->cssID[1]);

        if ($this->Template->headline == '') {
            $this->Template->headline = $this->headline;
        }

        if ($this->Template->hl == '') {
            $this->Template->hl = $this->hl;
        }

        return $this->Template->parse();
    }
}
