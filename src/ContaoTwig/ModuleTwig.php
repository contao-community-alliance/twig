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
 * Frontend module using a twig template as content.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ModuleTwig extends TwigModule
// @codingStandardsIgnoreEnd
{
    /**
     * Template name.
     *
     * @var string
     */
    protected $strTemplate = 'mod_twig';

    /**
     * Compile the content element.
     *
     * @return void
     */
    protected function compile()
    {
        $contaoTwig = ContaoTwig::getInstance();

        $contaoTwig
            ->getLoaderArray()
            ->setTemplate('module_' . $this->id, $this->twig);

        $this->Template->html = $contaoTwig
            ->getEnvironment()
            ->render('module_' . $this->id, $this->arrData);
    }
}
