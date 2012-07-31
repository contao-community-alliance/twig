<?php

abstract class TwigBackendModule extends BackendModule
{
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
