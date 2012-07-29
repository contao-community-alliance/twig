<?php

class TwigFrontendTemplate extends FrontendTemplate
{
	/**
	 * @var ContaoTwig
	 */
	protected $ContaoTwig;

	public function __construct($strTemplate = '', $strContentType = 'text/html')
	{
		parent::__construct($strTemplate, $strContentType);

		$this->import('ContaoTwig');
	}

	/**
	 * @return string
	 */
	public function parse()
	{
		if ($this->strTemplate == '')
		{
			return '';
		}

		// Override the output format in the front end
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->outputFormat != '')
			{
				$this->strFormat = $objPage->outputFormat;
			}

			$this->strTagEnding = ($this->strFormat == 'xhtml') ? ' />' : '>';
		}

		// HOOK: add custom parse filters
		if (isset($GLOBALS['TL_HOOKS']['parseTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this);
			}
		}

		$strFile = $this->strTemplate . '.' . $this->strFormat . '.twig';
		$strBuffer = $this->ContaoTwig->getEnvironment()->render($strFile, $this->arrData);

		// HOOK: add custom parse filters
		if (isset($GLOBALS['TL_HOOKS']['parseFrontendTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseFrontendTemplate']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseFrontendTemplate'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		return $strBuffer;
	}

}
