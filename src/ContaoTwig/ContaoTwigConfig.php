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
 * Class ContaoTwigConfig
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContaoTwigConfig
{
    protected $name = null;

    protected $allowDebugMode = true;

    protected $enableArrayLoader = true;

    protected $enableFilesystemLoader = true;

    protected $enableModuleTemplatesLoader = true;

    protected $enableThemeTemplatesLoader = true;

    protected $enableGlobalTemplatesLoader = true;

    protected $enableAutoescape = false;

    protected $setNumberFormat = true;

    protected $setDateFormat = true;

    protected $setTimeZone = true;

    protected $enableContaoExtension = true;

    protected $callInitializationHook = true;

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name !== null ? (string)$name : null;

        return $this;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $allowDebugMode
     */
    public function setAllowDebugMode($allowDebugMode)
    {
        $this->allowDebugMode = (bool)$allowDebugMode;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAllowDebugMode()
    {
        return $this->allowDebugMode;
    }

    /**
     * @param boolean $enableArrayLoader
     */
    public function setEnableArrayLoader($enableArrayLoader)
    {
        $this->enableArrayLoader = (bool)$enableArrayLoader;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableArrayLoader()
    {
        return $this->enableArrayLoader;
    }

    /**
     * @param boolean $enableFilesystemLoader
     */
    public function setEnableFilesystemLoader($enableFilesystemLoader)
    {
        $this->enableFilesystemLoader = $enableFilesystemLoader;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableFilesystemLoader()
    {
        return $this->enableFilesystemLoader;
    }

    /**
     * @param boolean $enableModuleTemplatesLoader
     */
    public function setEnableModuleTemplatesLoader($enableModuleTemplatesLoader)
    {
        $this->enableModuleTemplatesLoader = (bool)$enableModuleTemplatesLoader;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableModuleTemplatesLoader()
    {
        return $this->enableModuleTemplatesLoader;
    }

    /**
     * @param boolean $enableThemeTemplatesLoader
     */
    public function setEnableThemeTemplatesLoader($enableThemeTemplatesLoader)
    {
        $this->enableThemeTemplatesLoader = (bool)$enableThemeTemplatesLoader;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableThemeTemplatesLoader()
    {
        return $this->enableThemeTemplatesLoader;
    }

    /**
     * @param boolean $enableGlobalTemplatesLoader
     */
    public function setEnableGlobalTemplatesLoader($enableGlobalTemplatesLoader)
    {
        $this->enableGlobalTemplatesLoader = $enableGlobalTemplatesLoader;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableGlobalTemplatesLoader()
    {
        return $this->enableGlobalTemplatesLoader;
    }

    /**
     * @param boolean $enableAutoescape
     */
    public function setEnableAutoescape($enableAutoescape)
    {
        $this->enableAutoescape = (bool)$enableAutoescape;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableAutoescape()
    {
        return $this->enableAutoescape;
    }

    /**
     * @param boolean $setNumberFormat
     */
    public function setSetNumberFormat($setNumberFormat)
    {
        $this->setNumberFormat = (bool)$setNumberFormat;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSetNumberFormat()
    {
        return $this->setNumberFormat;
    }

    /**
     * @param boolean $setDateFormat
     */
    public function setSetDateFormat($setDateFormat)
    {
        $this->setDateFormat = (bool)$setDateFormat;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSetDateFormat()
    {
        return $this->setDateFormat;
    }

    /**
     * @param boolean $setTimeZone
     */
    public function setSetTimeZone($setTimeZone)
    {
        $this->setTimeZone = (bool)$setTimeZone;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSetTimeZone()
    {
        return $this->setTimeZone;
    }

    /**
     * @param boolean $enableContaoExtension
     */
    public function setEnableContaoExtension($enableContaoExtension)
    {
        $this->enableContaoExtension = (bool)$enableContaoExtension;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableContaoExtension()
    {
        return $this->enableContaoExtension;
    }

    /**
     * @param boolean $callInitializationHook
     */
    public function setCallInitializationHook($callInitializationHook)
    {
        $this->callInitializationHook = (bool)$callInitializationHook;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCallInitializationHook()
    {
        return $this->callInitializationHook;
    }

    function __toString()
    {
        $options = array();
        foreach ($this as $key => $value) {
            if ($value === true) {
                $options[] = $key;
            }
        }
        sort($options);

        return implode("\n", $options);
    }
}
