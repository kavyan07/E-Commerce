<?php
class Admin_Block_Category_Delete extends Core_Block_Template
{
     public function _construct()
    {
        
    }
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Admin/View/Category/delete.phtml"); 
    }
}




?>