<?php
class Admin_Block_Attribute_Edit extends Core_Block_Templet
{
     public function _construct()
    {
        
    }
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Admin/View/Attribute/edit.phtml"); 
    }
}




?>