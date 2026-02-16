<?php
class Page_Block_Root extends Core_Block_Templet
{
      public function __construct(){
        parent ::__construct();
        $this->setTemplate("Page/View/root.phtml");
      }
    public function _construct()
    {
        
        $head = Sdp::getBlock("page/head");
        $header = Sdp::getBlock("page/header");
        $footer = Sdp::getBlock("page/footer");
        $content = Sdp::getBlock("page/content");
        $home = Sdp::getBlock("page/home");
        $this->addChild("head", $head);
        $this->addChild("header", $header);
        $this->addChild("content", $content);
        $this->addChild("footer", $footer);
        $this->addChild("home", $home);
    }

}




?>