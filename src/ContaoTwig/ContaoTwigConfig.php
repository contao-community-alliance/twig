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
 * Class ContaoTwigConfig.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class ContaoTwigConfig
// @codingStandardsIgnoreEnd
{
    /**
     * The name of the config.
     *
     * @var null|string
     */
    protected $name = null;

    /**
     * Flag if debug mode shall be enabled.
     *
     * @var bool
     */
    protected $allowDebugMode = true;

    /**
     * Flag if the array loader shall be enabled.
     *
     * @var bool
     */
    protected $enableArrayLoader = true;

    /**
     * Flag if the file system loader shall be enabled.
     *
     * @var bool
     */
    protected $enableFilesystemLoader = true;

    /**
     * Flag if the module template loader shall be enabled.
     *
     * @var bool
     */
    protected $enableModuleTemplatesLoader = true;

    /**
     * Flag if the theme template loader shall be enabled.
     *
     * @var bool
     */
    protected $enableThemeTemplatesLoader = true;

    /**
     * Flag if the global template loader shall be enabled.
     *
     * @var bool
     */
    protected $enableGlobalTemplatesLoader = true;

    /**
     * Flag if the auto escaping shall be enabled.
     *
     * @var bool
     */
    protected $enableAutoescape = false;

    /**
     * Flag if the number formatting shall be enabled.
     *
     * @var bool
     */
    protected $setNumberFormat = true;

    /**
     * Flag if the date time formatting shall be enabled.
     *
     * @var bool
     */
    protected $setDateFormat = true;

    /**
     * Flag if the timezone shall be set.
     *
     * @var bool
     */
    protected $setTimeZone = true;

    /**
     * Flag if the Contao extension shall be enabled.
     *
     * @var bool
     */
    protected $enableContaoExtension = true;

    /**
     * Flag if the initialization HOOK shall be called.
     *
     * @var bool
     */
    protected $callInitializationHook = true;

    /**
     * Set the name of the config.
     *
     * @param null|string $name The name.
     *
     * @return ContaoTwigConfig
     */
    public function setName($name)
    {
        $this->name = ($name !== null) ? (string) $name : null;

        return $this;
    }

    /**
     * Retrieve the name of the config.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Enable or disable the debug mode.
     *
     * @param boolean $allowDebugMode The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setAllowDebugMode($allowDebugMode)
    {
        $this->allowDebugMode = (bool) $allowDebugMode;

        return $this;
    }

    /**
     * Check if the debug mode is allowed.
     *
     * @return boolean
     */
    public function isAllowDebugMode()
    {
        return $this->allowDebugMode;
    }

    /**
     * Enable or disable the array loader.
     *
     * @param boolean $enableArrayLoader The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableArrayLoader($enableArrayLoader)
    {
        $this->enableArrayLoader = (bool) $enableArrayLoader;

        return $this;
    }

    /**
     * Check if the array loader is enabled.
     *
     * @return boolean
     */
    public function isEnableArrayLoader()
    {
        return $this->enableArrayLoader;
    }

    /**
     * Enable or disable the file system loader.
     *
     * @param boolean $enableFilesystemLoader The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableFilesystemLoader($enableFilesystemLoader)
    {
        $this->enableFilesystemLoader = (bool) $enableFilesystemLoader;

        return $this;
    }

    /**
     * Check if the file system loader is enabled.
     *
     * @return boolean
     */
    public function isEnableFilesystemLoader()
    {
        return $this->enableFilesystemLoader;
    }

    /**
     * Enable or disable the module templates loader.
     *
     * @param boolean $enableModuleTemplatesLoader The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableModuleTemplatesLoader($enableModuleTemplatesLoader)
    {
        $this->enableModuleTemplatesLoader = (bool) $enableModuleTemplatesLoader;

        return $this;
    }

    /**
     * Check if the module templates loader is enabled.
     *
     * @return boolean
     */
    public function isEnableModuleTemplatesLoader()
    {
        return $this->enableModuleTemplatesLoader;
    }

    /**
     * Enable or disable the theme templates loader.
     *
     * @param boolean $enableThemeTemplatesLoader The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableThemeTemplatesLoader($enableThemeTemplatesLoader)
    {
        $this->enableThemeTemplatesLoader = (bool) $enableThemeTemplatesLoader;

        return $this;
    }

    /**
     * Check if the theme templates loader is enabled.
     *
     * @return boolean
     */
    public function isEnableThemeTemplatesLoader()
    {
        return $this->enableThemeTemplatesLoader;
    }

    /**
     * Enable or disable the global templates loader.
     *
     * @param boolean $enableGlobalTemplatesLoader The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableGlobalTemplatesLoader($enableGlobalTemplatesLoader)
    {
        $this->enableGlobalTemplatesLoader = $enableGlobalTemplatesLoader;

        return $this;
    }

    /**
     * Check if the global templates loader is enabled.
     *
     * @return boolean
     */
    public function isEnableGlobalTemplatesLoader()
    {
        return $this->enableGlobalTemplatesLoader;
    }

    /**
     * Enable or disable auto escaping.
     *
     * @param boolean $enableAutoescape The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableAutoescape($enableAutoescape)
    {
        $this->enableAutoescape = (bool) $enableAutoescape;

        return $this;
    }

    /**
     * Check if auto escaping is enabled.
     *
     * @return boolean
     */
    public function isEnableAutoescape()
    {
        return $this->enableAutoescape;
    }

    /**
     * Set if number formatting shall be enabled.
     *
     * @param boolean $setNumberFormat The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setSetNumberFormat($setNumberFormat)
    {
        $this->setNumberFormat = (bool) $setNumberFormat;

        return $this;
    }

    /**
     * Check if number formatting is enabled.
     *
     * @return boolean
     */
    public function isSetNumberFormat()
    {
        return $this->setNumberFormat;
    }

    /**
     * Enable or disable formatting of date time values.
     *
     * @param boolean $setDateFormat The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setSetDateFormat($setDateFormat)
    {
        $this->setDateFormat = (bool) $setDateFormat;

        return $this;
    }

    /**
     * Check if date time formatting is enabled.
     *
     * @return boolean
     */
    public function isSetDateFormat()
    {
        return $this->setDateFormat;
    }

    /**
     * Set if the timezone shall be set.
     *
     * @param boolean $setTimeZone The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setSetTimeZone($setTimeZone)
    {
        $this->setTimeZone = (bool) $setTimeZone;

        return $this;
    }

    /**
     * Check if setting of the timezone is desired.
     *
     * @return boolean
     */
    public function isSetTimeZone()
    {
        return $this->setTimeZone;
    }

    /**
     * Set if the Contao twig extension shall be enabled.
     *
     * @param boolean $enableContaoExtension The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setEnableContaoExtension($enableContaoExtension)
    {
        $this->enableContaoExtension = (bool) $enableContaoExtension;

        return $this;
    }

    /**
     * Check if enabling of the contao extension is desired.
     *
     * @return boolean
     */
    public function isEnableContaoExtension()
    {
        return $this->enableContaoExtension;
    }

    /**
     * Set if the initialization hook shall be called.
     *
     * @param boolean $callInitializationHook The desired value.
     *
     * @return ContaoTwigConfig
     */
    public function setCallInitializationHook($callInitializationHook)
    {
        $this->callInitializationHook = (bool) $callInitializationHook;

        return $this;
    }

    /**
     * Check if the initialization hook shall be called.
     *
     * @return boolean
     */
    public function isCallInitializationHook()
    {
        return $this->callInitializationHook;
    }

    /**
     * Render a string representation of the config.
     *
     * @return string
     */
    public function __toString()
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
