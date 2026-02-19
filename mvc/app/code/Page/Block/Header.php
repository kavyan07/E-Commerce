<?php
/**
 * Page_Block_Header
 *
 * Orchestrates the modular header by wiring three child blocks:
 *
 *   menu   → Page_Block_Header_Menu   (navigation links)
 *   search → Page_Block_Header_Search (search bar)
 *   icon   → Page_Block_Header_Icon   (profile dropdown)
 *
 * Template: Page/View/header.phtml
 *
 * Flow:
 *   Core_Block_Templet::__construct()
 *     └─ calls $this->_construct()   ← child blocks registered here
 *   Root calls $this->toHtml()
 *     └─ includes header.phtml
 *          └─ $this->getChildHtml('menu')   → Page_Block_Header_Menu::toHtml()
 *          └─ $this->getChildHtml('search') → Page_Block_Header_Search::toHtml()
 *          └─ $this->getChildHtml('icon')   → Page_Block_Header_Icon::toHtml()
 */
class Page_Block_Header extends Core_Block_Templet
{
    public function __construct()
    {
        parent::__construct(); // triggers _construct() below
        $this->setTemplate('Page/View/header.phtml');
    }

    /**
     * Register child blocks.
     * Called automatically by Core_Block_Templet::__construct().
     */
    public function _construct()
    {
        $this->addChild('menu',   new Page_Block_Header_Menu());
        $this->addChild('search', new Page_Block_Header_Search());
        $this->addChild('icon',   new Page_Block_Header_Icon());
    }
}