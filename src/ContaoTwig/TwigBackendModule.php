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
 * A BackendModule implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
abstract class TwigBackendModule extends BackendModule
// @codingStandardsIgnoreEnd
{
    /**
     * The template instance.
     *
     * @var TwigBackendTemplate
     *
     * @codingStandardsIgnoreStart
     */
    protected $Template;
    // @codingStandardsIgnoreEnd

    /**
     * Parse the template.
     *
     * @return string
     */
    public function generate()
    {
        $this->Template = new TwigBackendTemplate($this->strTemplate);
        $this->compile();

        return $this->Template->parse();
    }
}
