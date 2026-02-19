<?php
class Page_Block_Head extends Core_Block_Templet
{
    protected  $_js=[];
    protected $_css=[];
    public function __construct()
    {
          $this->setTemplate("Page/View/head.phtml");  
          $this->addJs("js/default.js")
               ->addJs("js/default1.js" );
               
            $this->addCss("skin/css/style.css")
               ->addCss("skin/css/style.css" );      
    }
    public function addJs($files){
        $this->_js[]=$files;
        return  $this;
    }
    public function getJs(){
        return $this->_js;
    }
      public function addCss($files){
        $this->_css[]=$files;
        return  $this;
    }
    public function getCss(){
        return $this->_css;
    }
    

   
}





?>