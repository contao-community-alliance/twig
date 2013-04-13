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
 * Class ContentTwig
 *
 * Content element using a twig template as content.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class ContentTwig extends TwigContentElement
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_twig';

	/**
	 * Compile the content element
	 */
	protected function compile()
	{
		$contaoTwig = ContaoTwig::getInstance();

		$contaoTwig
			->getLoaderArray()
			->setTemplate('content_' . $this->id, $this->twig);

		$this->Template->html = $contaoTwig
			->getEnvironment()
			->render('content_' . $this->id, $this->arrData);
	}
}
