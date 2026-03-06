<?php
class Admin_Block_Attribute_List extends Core_Block_Template
{
     public function _construct()
    {
        
    }
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Admin/View/Attribute/list.phtml"); 
    }
}




?>