<?php
class Page_Controllers_Index
{


    public function indexAction()
    {
       
        $root = Sdp::getBlock("page/root");
        $home = Sdp::getBlock("page/home");
        $root->getChild("content")->addChild("home", $home);
         
        // print_r($head);
        //  print_r($header);
        // print_r($root);
        // echo "</pre>";
        $root->toHtml();
       // print_r($root);
        
    }



}


?>