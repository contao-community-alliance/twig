<?php

/**
 * Twig Integration for the Contao OpenSource CMS
 *
 * @package ContaoTwig
 * @link    https://github.com/InfinitySoft/contao-twig SCM
 * @link    http://de.contaowiki.org/Twig Wiki
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class TwigCustomPagination
 *
 * A specialised TwigPagination implementation that build the pagination from an array of links.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@infinitysoft.de>
 */
class TwigCustomPagination
    extends TwigPagination
{
    /**
     * @var array
     */
    protected $arrLinks;

    /**
     * @var string
     */
    protected $strParam;

    /**
     * @param array $arrLinks
     * @param int   $intNumberOfLinks
     */
    public function __construct(array $arrLinks,
                                $intNumberOfLinks = 7)
    {
        parent::__construct(count($arrLinks),
                            1,
                            $intNumberOfLinks);

        $this->arrLinks = array_values($arrLinks);
    }

    /**
     * Generate the pagination menu and return it as HTML string
     *
     * @param string
     *
     * @return string
     */
    public function generate($strSeparator = '')
    {
        $this->intTotalPages = $this->intRows;

        // Return if there is only one page
        if ($this->intTotalPages < 2 || $this->intRows < 1) {
            return '';
        }

        $this->intPage = -1;
        $strRequest    = rawurldecode($this->Environment->request);
        foreach ($this->arrLinks as $intPage => $strLink) {
            if ($strRequest == rawurldecode($strLink)) {
                $this->intPage = $intPage + 1;
                break;
            }
        }

        if ($this->intPage == -1) {
            return '';
        }

        if ($this->intPage > $this->intTotalPages) {
            $this->intPage = $this->intTotalPages;
        }

        $this->Template = new TwigFrontendTemplate('pagination');

        $this->Template->hasFirst    = $this->hasFirst();
        $this->Template->hasPrevious = $this->hasPrevious();
        $this->Template->hasNext     = $this->hasNext();
        $this->Template->hasLast     = $this->hasLast();

        $this->Template->items = $this->getItems();
        $this->Template->page  = $this->intPage;
        $this->Template->total = $this->intTotalPages;

        $this->Template->first = array
        (
            'page'  => 1,
            'link'  => $this->lblFirst,
            'href'  => $this->arrLinks[0],
        );

        $this->Template->previous = array
        (
            'page'  => $this->intPage - 1,
            'link'  => $this->lblPrevious,
            'href'  => $this->arrLinks[$this->intPage - 2],
        );

        $this->Template->next = array
        (
            'page'  => $this->intPage + 1,
            'link'  => $this->lblNext,
            'href'  => $this->arrLinks[$this->intPage],
        );

        $this->Template->last = array
        (
            'page'  => $this->intTotalPages,
            'link'  => $this->lblLast,
            'href'  => $this->arrLinks[count($this->arrLinks) - 1],
        );

        global $objPage;
        $strTagClose = ($objPage->outputFormat == 'xhtml')
            ? ' />'
            : '>';

        // Add rel="prev" and rel="next" links (see #3515)
        if ($this->hasPrevious()) {
            $GLOBALS['TL_HEAD'][] = '<link rel="prev" href="' . $this->linkToPage($this->intPage - 1) . '"' . $strTagClose;
        }
        if ($this->hasNext()) {
            $GLOBALS['TL_HEAD'][] = '<link rel="next" href="' . $this->linkToPage($this->intPage + 1) . '"' . $strTagClose;
        }

        return $this->Template->parse();
    }


    /**
     * Generate all page links separated with the given argument and return them as array
     *
     * @param string
     *
     * @return string
     */
    public function getItems()
    {
        $arrLinks = array();

        $intNumberOfLinks = floor($this->intNumberOfLinks / 2);
        $intFirstOffset   = $this->intPage - $intNumberOfLinks - 1;

        if ($intFirstOffset > 0) {
            $intFirstOffset = 0;
        }

        $intLastOffset = $this->intPage + $intNumberOfLinks - $this->intTotalPages;

        if ($intLastOffset < 0) {
            $intLastOffset = 0;
        }

        $intFirstLink = $this->intPage - $intNumberOfLinks - $intLastOffset;

        if ($intFirstLink < 1) {
            $intFirstLink = 1;
        }

        $intLastLink = $this->intPage + $intNumberOfLinks - $intFirstOffset;

        if ($intLastLink > $this->intTotalPages) {
            $intLastLink = $this->intTotalPages;
        }

        for ($i = $intFirstLink; $i <= $intLastLink; $i++) {
            $arrLinks[] = (object) array(
                'page'    => $i,
                'current' => $i == $this->intPage,
                'href'    => $this->arrLinks[$i - 1]
            );
        }

        return $arrLinks;
    }
}
