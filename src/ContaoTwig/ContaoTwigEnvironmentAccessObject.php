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
 * Class ContaoTwigEnvironmentAccessObject
 *
 * Provide access to environment.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwigEnvironmentAccessObject
{
    /**
     * The environment object.
     *
     * @var Environment
     */
    protected $environment;

    public function __construct()
    {
        $this->environment = Environment::getInstance();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->environment->$key;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '{environment}';
    }
}
