<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/InfinitySoft/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * A specialised TwigPagination implementation that build the pagination from an array of links.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class TwigCustomPagination extends TwigPagination
// @codingStandardsIgnoreEnd
{
    /**
     * The links to generate the pagination from.
     *
     * @var array
     */
    protected $arrLinks;

    /**
     * Create a new instance.
     *
     * @param array $arrLinks         The links to generate the pagination from.
     *
     * @param int   $intNumberOfLinks The amount of links to display.
     */
    public function __construct(array $arrLinks, $intNumberOfLinks = 7)
    {
        parent::__construct(count($arrLinks), 1, $intNumberOfLinks);

        $this->arrLinks = array_values($arrLinks);
    }

    /**
     * Generate the pagination menu and return it as HTML string.
     *
     * @param string $strSeparator Unused in this implementation.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function generate($strSeparator = '')
    {
        $this->determineValues();

        // Return if there is only one page
        if ($this->intTotalPages < 2 || $this->intRows < 1) {
            return '';
        }

        if ($this->intPage == -1) {
            return '';
        }

        $template = new TwigFrontendTemplate('pagination');

        $template->hasFirst    = $this->hasFirst();
        $template->hasPrevious = $this->hasPrevious();
        $template->hasNext     = $this->hasNext();
        $template->hasLast     = $this->hasLast();

        $template->items = $this->getItems();
        $template->page  = $this->intPage;
        $template->total = $this->intTotalPages;

        $template->first = array
        (
            'page' => 1,
            'link' => $this->lblFirst,
            'href' => $this->arrLinks[0],
        );

        $template->previous = array
        (
            'page' => $this->intPage - 1,
            'link' => $this->lblPrevious,
            'href' => $this->arrLinks[($this->intPage - 2)],
        );

        $template->next = array
        (
            'page' => $this->intPage + 1,
            'link' => $this->lblNext,
            'href' => $this->arrLinks[$this->intPage],
        );

        $template->last = array
        (
            'page' => $this->intTotalPages,
            'link' => $this->lblLast,
            'href' => $this->arrLinks[(count($this->arrLinks) - 1)],
        );

        $this->setHeadTags();

        return $template->parse();
    }

    /**
     * Retrieve the link to an item.
     *
     * @param int $item The item index.
     *
     * @return string
     */
    protected function getItemLink($item)
    {
        return $this->arrLinks[($item - 1)];
    }

    /**
     * Determine the basic values like the url and the total amount of pages.
     *
     * @return void
     */
    protected function determineValues()
    {
        $this->intTotalPages = $this->intRows;

        $this->intPage = -1;
        $strRequest    = rawurldecode(\Environment::get('request'));
        foreach ($this->arrLinks as $intPage => $strLink) {
            if ($strRequest == rawurldecode($strLink)) {
                $this->intPage = ($intPage + 1);
                break;
            }
        }

        if ($this->intPage > $this->intTotalPages) {
            $this->intPage = $this->intTotalPages;
        }
    }
}
