<?php
class Catalog_Controllers_Product extends Core_Controllers_Front
{
    // public function listAction(){
    //     echo "list action";
    // }
    public function viewAction()
    {
        $root = Sdp::getBlock("page/root");
        $view = Sdp ::getBlock("catalog/product_view");
        
          $root->getChild('content')->addChild('view',$view);
           $root->getChild('head')->addJs('js/catalog/view.js',$view);
    
        // // print_r($head);
        // // print_r($header);
        // print_r($root);
        // echo "</pre>";

        $root->toHtml();
        // print_r($root);
    }
      public function listAction(){
        $root = Sdp::getBlock('page/root');
        $list = Sdp ::getBlock("catalog/product_list");
        $root->getChild('content')->addChild('view',$list);
         $root->toHtml();
    }
  
}



?>