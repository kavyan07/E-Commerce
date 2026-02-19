<?php
/**
 * Page_Block_Header_Icon
 *
 * Reads session state and exposes user-account data to icon.phtml.
 * No HTML is produced here — pure data/session methods only.
 *
 * Template : Page/View/header/icon.phtml
 */
class Page_Block_Header_Icon extends Core_Block_Templet
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('Page/View/header/icon.phtml');
    }

    public function _construct() {}

    /**
     * Whether a user is currently logged in.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Sanitised display name of the logged-in user.
     *
     * @return string  Empty string when not logged in.
     */
    public function getUserName()
    {
        if (!$this->isLoggedIn()) {
            return '';
        }
        return htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitised email address of the logged-in user.
     *
     * @return string  Empty string when not logged in.
     */
    public function getUserEmail()
    {
        if (!$this->isLoggedIn()) {
            return '';
        }
        return htmlspecialchars($_SESSION['user_email'] ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Single uppercase letter used as the avatar initial.
     *
     * @return string  Empty string when not logged in.
     */
    public function getUserInitial()
    {
        $name = $this->getUserName();
        return $name ? strtoupper($name[0]) : '';
    }

    /**
     * Dropdown items shown when the user IS logged in.
     *
     * Each item: ['id', 'label', 'url', 'icon', 'class' (optional)]
     *
     * @return array[]
     */
    public function getLoggedInItems()
    {
        return [
            [
                'id'    => 'dd-profile',
                'label' => 'My Profile',
                'url'   => '/account/profile',
                'icon'  => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>',
                'class' => 'dropdown-item',
            ],
            [
                'id'    => 'dd-orders',
                'label' => 'My Orders',
                'url'   => '/account/orders',
                'icon'  => '<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="1"/>
                            <line x1="9" y1="12" x2="15" y2="12"/>
                            <line x1="9" y1="16" x2="13" y2="16"/>',
                'class' => 'dropdown-item',
            ],
            [
                'id'    => 'dd-logout',
                'label' => 'Logout',
                'url'   => '/account/logout',
                'icon'  => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>',
                'class' => 'dropdown-item logout-item',
                'divider_before' => true,
            ],
        ];
    }

    /**
     * Dropdown items shown when the user is a GUEST.
     *
     * @return array[]
     */
    public function getGuestItems()
    {
        return [
            [
                'id'    => 'dd-login',
                'label' => 'Login',
                'url'   => '/account/login',
                'icon'  => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>',
                'class' => 'dropdown-item',
            ],
            [
                'id'    => 'dd-register',
                'label' => 'Create Account',
                'url'   => '/account/register',
                'icon'  => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <line x1="19" y1="8" x2="19" y2="14"/>
                            <line x1="22" y1="11" x2="16" y2="11"/>',
                'class' => 'dropdown-item',
            ],
        ];
    }
}
