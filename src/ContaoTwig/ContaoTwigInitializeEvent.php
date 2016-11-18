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
 * Class ContaoTwigInitializeEvent.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigInitializeEvent extends \Symfony\Component\EventDispatcher\Event
// @codingStandardsIgnoreEnd
{
    /**
     * The twig instance.
     *
     * @var ContaoTwig
     */
    protected $contaoTwig;

    /**
     * Create a new instance.
     *
     * @param ContaoTwig $contaoTwig The twig instance to use.
     */
    public function __construct(ContaoTwig $contaoTwig)
    {
        $this->contaoTwig = $contaoTwig;
    }

    /**
     * Return the twig instance.
     *
     * @return \ContaoTwig
     */
    public function getContaoTwig()
    {
        return $this->contaoTwig;
    }
}
