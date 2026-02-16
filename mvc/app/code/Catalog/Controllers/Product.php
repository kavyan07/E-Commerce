<?php
class Catalog_Controllers_Product
{
    // public function listAction(){
    //     echo "list action";
    // }
    public function viewAction()
    {
        $root = Sdp::getBlock("page/root");
        $view = Sdp ::getBlock("catalog/product_view");
        $root->getChild('content')->addChild('view',$view);
        

        // // print_r($head);
        // // print_r($header);
        // print_r($root);
        // echo "</pre>";

        $root->toHtml();
        // print_r($root);
    }
}



?>