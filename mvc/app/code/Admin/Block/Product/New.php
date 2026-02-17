<?php
class Admin_Block_Product_New extends Core_Block_Templet
{
     public function _construct()
    {
        
    }
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Admin/View/Product/new.phtml"); 
    }
}




?>