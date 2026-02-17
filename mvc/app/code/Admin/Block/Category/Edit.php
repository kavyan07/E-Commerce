<?php
class Admin_Block_Category_Edit extends Core_Block_Templet
{
     public function _construct()
    {
        
    }
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Admin/View/Category/edit.phtml"); 
    }
}




?>