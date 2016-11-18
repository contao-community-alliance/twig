<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/bit3/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author  Sven Baumann <baumann.sv@gmail.com>
 * @author  David Molineus <david.molineus@netzmacht.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * A generic template implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class TwigTemplate
// @codingStandardsIgnoreEnd
{
    /**
     * The template name.
     *
     * @var string
     */
    protected $templateName;

    /**
     * The template format.
     *
     * @var string
     */
    protected $format;

    /**
     * The template file extension.
     *
     * @var string
     */
    protected $fileExtension;

    /**
     * Create a new instance.
     *
     * @param string $templateName  The template name.
     *
     * @param string $format        The template format.
     *
     * @param string $fileExtension The template file extension.
     */
    public function __construct($templateName = null, $format = null, $fileExtension = 'twig')
    {
        $this->templateName  = $templateName;
        $this->format        = $format;
        $this->fileExtension = $fileExtension;
    }

    /**
     * Set the template name.
     *
     * @param string $template The new value.
     *
     * @return TwigTemplate
     */
    public function setTemplateName($template)
    {
        $this->templateName = $template;

        return $this;
    }

    /**
     * Retrieve the template name.
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Set the template format.
     *
     * @param string $format The new value.
     *
     * @return TwigTemplate
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Retrieve the template format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the template file extension.
     *
     * @param string $fileExtension The new value.
     *
     * @return TwigTemplate
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    /**
     * Retrieve the template file extension.
     *
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Get the effective template file.
     *
     * @return string
     *
     * @throws RuntimeException When no template name has been defined.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getTemplateFile()
    {
        if (!$this->templateName) {
            throw new RuntimeException('No template defined');
        }

        $format = null;
        if (TL_MODE == 'FE' &&
            $this->format === null &&
            $GLOBALS['objPage'] &&
            $GLOBALS['objPage']->outputFormat != ''
        ) {
            $format = $GLOBALS['objPage']->outputFormat;
        } else {
            if ($this->format !== null) {
                $format = $this->format;
            }
        }

        return $this->templateName . ($format ? '.' . $format : '') . '.' . $this->fileExtension;
    }

    /**
     * Parse the template.
     *
     * @param array $context The context to use.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function parse(array $context = array())
    {
        $file = $this->getTemplateFile();

        // HOOK: add custom parse filters
        if (isset($GLOBALS['TL_HOOKS']['prepareTwigTemplate']) &&
            is_array($GLOBALS['TL_HOOKS']['prepareTwigTemplate'])
        ) {
            foreach ($GLOBALS['TL_HOOKS']['prepareTwigTemplate'] as $callback) {
                $object = \System::importStatic($callback[0]);
                $object->{$callback[1]}($this, $context);
            }
        }

        $contaoTwig  = ContaoTwig::getInstance();
        $environment = $contaoTwig->getEnvironment();
        $buffer      = $environment->render($file, $context);

        // HOOK: add custom parse filters
        if (isset($GLOBALS['TL_HOOKS']['parseTwigTemplate']) &&
            is_array($GLOBALS['TL_HOOKS']['parseTwigTemplate'])
        ) {
            foreach ($GLOBALS['TL_HOOKS']['parseTwigTemplate'] as $callback) {
                $object = \System::importStatic($callback[0]);
                $buffer = $object->{$callback[1]}(
                    $buffer,
                    $context,
                    $this
                );
            }
        }

        return $buffer;
    }
}
