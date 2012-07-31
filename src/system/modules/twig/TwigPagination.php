<?php

class TwigPagination extends Pagination
{
	/**
	 * Generate the pagination menu and return it as HTML string
	 *
	 * @param string
	 *
	 * @return string
	 */
	public function generate()
	{
		if ($this->intRowsPerPage < 1) {
			return '';
		}

		$this->strUrl = preg_replace(
			array('#\?page=\d+&#i', '#\?page=\d+$#i', '#&(amp;)?page=\d+#i'),
			array('?', '', ''),
			$this->Environment->request
		);

		$this->strVarConnector = strpos($this->strUrl, '?') !== false ? '&amp;' : '?';
		$this->intTotalPages   = ceil($this->intRows / $this->intRowsPerPage);

		// Return if there is only one page
		if ($this->intTotalPages < 2 || $this->intRows < 1) {
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
			'href'  => $this->linkToPage(1),
		);

		$this->Template->previous = array
		(
			'page'  => $this->intPage - 1,
			'link'  => $this->lblPrevious,
			'href'  => $this->linkToPage($this->intPage - 1),
		);

		$this->Template->next = array
		(
			'page'  => $this->intPage + 1,
			'link'  => $this->lblNext,
			'href'  => $this->linkToPage($this->intPage + 1),
		);

		$this->Template->last = array
		(
			'page'  => $this->intTotalPages,
			'link'  => $this->lblLast,
			'href'  => $this->linkToPage($this->intTotalPages),
		);

		global $objPage;
		$strTagClose = ($objPage->outputFormat == 'xhtml') ? ' />' : '>';

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
				'href'    => $this->linkToPage($i)
			);
		}

		return $arrLinks;
	}
}
