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
 * A Pagination implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
// @codingStandardsIgnoreStart - class is not within a namespace - this will change with next major.
class TwigPagination extends Pagination
// @codingStandardsIgnoreEnd
{
    /**
     * Generate the pagination menu and return it as HTML string.
     *
     * @param string $strSeparator Ignored in this implementation.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function generate($strSeparator = '')
    {
        if ($this->intRowsPerPage < 1) {
            return '';
        }

        $this->determineValues();

        // Return if there is only one page
        if ($this->intTotalPages < 2 || $this->intRows < 1) {
            return '';
        }

        if ($this->intPage > $this->intTotalPages) {
            $this->intPage = $this->intTotalPages;
        }

        $template = $this->prepareTemplate();

        $this->setHeadTags();

        return $template->parse();
    }

    /**
     * Generate all page links separated with the given argument and return them as array.
     *
     * @return string
     */
    public function getItems()
    {
        $arrLinks = array();

        $intNumberOfLinks = floor($this->intNumberOfLinks / 2);
        $intFirstOffset   = ($this->intPage - $intNumberOfLinks - 1);

        if ($intFirstOffset > 0) {
            $intFirstOffset = 0;
        }

        $intLastOffset = ($this->intPage + $intNumberOfLinks - $this->intTotalPages);

        if ($intLastOffset < 0) {
            $intLastOffset = 0;
        }

        $intFirstLink = ($this->intPage - $intNumberOfLinks - $intLastOffset);

        if ($intFirstLink < 1) {
            $intFirstLink = 1;
        }

        $intLastLink = ($this->intPage + $intNumberOfLinks - $intFirstOffset);

        if ($intLastLink > $this->intTotalPages) {
            $intLastLink = $this->intTotalPages;
        }

        for ($i = $intFirstLink; $i <= $intLastLink; $i++) {
            $arrLinks[] = (object) array(
                'page'    => $i,
                'current' => $i == $this->intPage,
                'href'    => $this->getItemLink($i)
            );
        }

        return $arrLinks;
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
        return $this->linkToPage($item);
    }

    /**
     * Determine the basic values like the url and the total amount of pages.
     *
     * @return void
     */
    protected function determineValues()
    {
        $this->strUrl = preg_replace(
            array(
                '#\?page=\d+&#i',
                '#\?page=\d+$#i',
                '#&(amp;)?page=\d+#i'
            ),
            array(
                '?',
                '',
                ''
            ),
            \Environment::get('request')
        );

        $this->strVarConnector = strpos(
            $this->strUrl,
            '?'
        ) !== false
            ? '&amp;'
            : '?';
        $this->intTotalPages   = ceil($this->intRows / $this->intRowsPerPage);
    }

    /**
     * Build the template instance and return it.
     *
     * @return TwigFrontendTemplate
     */
    private function prepareTemplate()
    {
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
            'href' => $this->linkToPage(1),
        );

        $template->previous = array
        (
            'page' => $this->intPage - 1,
            'link' => $this->lblPrevious,
            'href' => $this->linkToPage($this->intPage - 1),
        );

        $template->next = array
        (
            'page' => $this->intPage + 1,
            'link' => $this->lblNext,
            'href' => $this->linkToPage($this->intPage + 1),
        );

        $template->last = array
        (
            'page' => $this->intTotalPages,
            'link' => $this->lblLast,
            'href' => $this->linkToPage($this->intTotalPages),
        );

        return $template;
    }

    /**
     * Set the head tags (link rel next/prev).
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function setHeadTags()
    {
        $strTagClose = ($GLOBALS['objPage']->outputFormat == 'xhtml')
            ? ' />'
            : '>';

        // Add rel="prev" and rel="next" links (see #3515)
        if ($this->hasPrevious()) {
            $GLOBALS['TL_HEAD'][] = '<link rel="prev" href="' .
                $this->linkToPage($this->intPage - 1) .
                '"' . $strTagClose;
        }
        if ($this->hasNext()) {
            $GLOBALS['TL_HEAD'][] = '<link rel="next" href="' .
                $this->linkToPage($this->intPage + 1) .
                '"' . $strTagClose;
        }
    }
}
