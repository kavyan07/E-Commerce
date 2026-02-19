<?php
/**
 * Page_Block_Header_Search
 *
 * Supplies search-bar configuration to search.phtml.
 * No HTML is produced here — pure data/config methods only.
 *
 * Template : Page/View/header/search.phtml
 */
class Page_Block_Header_Search extends Core_Block_Templet
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('Page/View/header/search.phtml');
    }

    public function _construct() {}

    /**
     * The URL the search form posts / redirects to.
     *
     * @return string
     */
    public function getActionUrl()
    {
        return '/catalog/product';
    }

    /**
     * The query-string parameter name used for the search term.
     *
     * @return string
     */
    public function getQueryParam()
    {
        return 'q';
    }

    /**
     * Placeholder text shown inside the search input.
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return 'Search products…';
    }

    /**
     * Returns the current search query from the request (if any),
     * so the input can be pre-filled on results pages.
     *
     * @return string
     */
    public function getCurrentQuery()
    {
        $raw = $_GET[$this->getQueryParam()] ?? '';
        return htmlspecialchars(trim($raw), ENT_QUOTES, 'UTF-8');
    }
}
