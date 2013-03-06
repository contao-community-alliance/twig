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
 * Class TwigModule
 *
 * A Module implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
abstract class TwigModule
	extends Module
{
	/**
	 * @var TwigFrontendTemplate
	 */
	protected $Template;

	/**
	 * Parse the template
	 *
	 * @return string
	 */
	public function generate()
	{
		if ($this->arrData['space'][0] != '') {
			$this->arrStyle[] = 'margin-top:' . $this->arrData['space'][0] . 'px;';
		}

		if ($this->arrData['space'][1] != '') {
			$this->arrStyle[] = 'margin-bottom:' . $this->arrData['space'][1] . 'px;';
		}

		$this->Template = new TwigFrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);

		$this->compile();

		$this->Template->inColumn = $this->strColumn;
		$this->Template->style    = !empty($this->arrStyle)
			? implode(
				' ',
				$this->arrStyle
			)
			: '';
		$this->Template->cssID    = ($this->cssID[0] != '')
			? ' id="' . $this->cssID[0] . '"'
			: '';
		$this->Template->class    = trim('mod_' . $this->type . ' ' . $this->cssID[1]);

		if ($this->Template->headline == '') {
			$this->Template->headline = $this->headline;
		}

		if ($this->Template->hl == '') {
			$this->Template->hl = $this->hl;
		}

		return $this->Template->parse();
	}
}