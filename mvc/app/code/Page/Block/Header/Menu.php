<?php
/**
 * Page_Block_Header_Menu
 *
 * Supplies navigation link data to menu.phtml.
 * No HTML is produced here — pure data methods only.
 *
 * Template : Page/View/header/menu.phtml
 */
class Page_Block_Header_Menu extends Core_Block_Templet
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('Page/View/header/menu.phtml');
    }

    public function _construct() {}

    /**
     * Returns the ordered list of navigation items.
     *
     * Each item is an associative array:
     *   'id'    => unique HTML id suffix used on the <a> element
     *   'label' => visible link text
     *   'url'   => href value
     *   'icon'  => SVG <path>/<circle>/etc. inner markup (no <svg> wrapper)
     *
     * @return array[]
     */
    public function getNavItems()
    {
        return [
            [
                'id'    => 'home',
                'label' => 'Home',
                'url'   => '/',
                'icon'  => '<path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
                            <polyline points="9 21 9 12 15 12 15 21"/>',
            ],
            [
                'id'    => 'product',
                'label' => 'Product',
                'url'   => '/catalog/product',
                'icon'  => '<rect x="2" y="3" width="20" height="14" rx="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>',
            ],
            [
                'id'    => 'cart',
                'label' => 'Cart',
                'url'   => '/cart',
                'icon'  => '<circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
            ],
        ];
    }

    /**
     * Returns the total number of items currently in the session cart.
     *
     * @return int
     */
    public function getCartCount()
    {
        $count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += (int)($item['qty'] ?? 1);
            }
        }
        return $count;
    }
}
