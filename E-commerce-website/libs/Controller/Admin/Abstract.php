<?php
// Abstract Controller with Auth Check
abstract class Controller_Admin_Abstract extends Controller_Abstract
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['admin_user'])) {
            header('Location: /E-commerce-website/admin/login');
            exit;
        }
    }
}
