<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/bit3/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class TwigTemplate
 *
 * A generic template implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class TwigTemplate
{
	/**
	 * @var string
	 */
	protected $templateName;

	/**
	 * @var string
	 */
	protected $format;

	/**
	 * @var string
	 */
	protected $fileExtension;

	/**
	 * @param string $templateName
	 * @param string $format
	 * @param string $contentType
	 */
	public function __construct($templateName = null, $format = null, $fileExtension = 'twig')
	{
		$this->templateName  = $templateName;
		$this->format        = $format;
		$this->fileExtension = $fileExtension;
	}

	/**
	 * @param string $template
	 */
	public function setTemplateName($template)
	{
		$this->templateName = $template;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->templateName;
	}

	/**
	 * @param string $format
	 */
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * @param string $fileExtension
	 */
	public function setFileExtension($fileExtension)
	{
		$this->fileExtension = $fileExtension;
		return $this;
	}

	/**
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
	 * @throws RuntimeException
	 */
	public function getTemplateFile()
	{
		if (!$this->templateName) {
			throw new RuntimeException('No template defined');
		}

		if (TL_MODE == 'FE' &&
			$this->format === null &&
			$GLOBALS['objPage'] &&
			$GLOBALS['objPage']->outputFormat != ''
		) {
			$format = $GLOBALS['objPage']->outputFormat;
		}
		else if ($this->format !== null) {
			$format = $this->format;
		}

		return $this->templateName . ($format ? '.' . $format : '') . '.' . $this->fileExtension;
	}

	/**
	 * @return string
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
				$object->$callback[1]($this);
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
				$buffer = $object->$callback[1](
					$buffer,
					$context,
					$this
				);
			}
		}

		return $buffer;
	}

}
