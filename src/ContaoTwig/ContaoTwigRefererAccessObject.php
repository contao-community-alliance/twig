<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/bit3/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Oliver Hoff <oliver@hofff.com>
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Provide access to the Contao referer.
 *
 * @package ContaoTwig
 * @author  Oliver Hoff <oliver@hofff.com>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigRefererAccessObject extends System
// @codingStandardsIgnoreEnd
{
    /**
     * Create a new instance.
     *
     * @codingStandardsIgnoreStart - Overriding is not useless as we change the visibility to public.
     */
    public function __construct()
    {
        parent::__construct();
    }
    // @codingStandardsIgnoreEnd

    /**
     * Generate a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->current();
    }

    /**
     * Retrieve the current referrer.
     *
     * @return string
     */
    public function current()
    {
        if (version_compare(VERSION, '3', '>=')) {
            return System::getReferer();
        } else {
            return $this->getReferer();
        }
    }
}
