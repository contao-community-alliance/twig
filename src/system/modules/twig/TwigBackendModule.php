<?php

abstract class TwigBackendModule extends BackendModule
{
	/**
	 * @var TwigBackendTemplate
	 */
	protected $Template;

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$this->Template = new TwigBackendTemplate($this->strTemplate);
		$this->compile();

		return $this->Template->parse();
	}
}
