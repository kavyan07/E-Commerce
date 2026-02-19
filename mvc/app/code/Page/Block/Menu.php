<?php
class Page_Block_Menu extends Core_Block_Templet
{
    
    public function __construct()
    {    
        $this->setTemplate("Page/View/menu.phtml");
    }

    public function getMenuArray()
    {
        return ['category1' => 'Home', 'category2' => 'Product', 'category3' => 'Login'];
        
    }

}





?>