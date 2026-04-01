<?php

class Controller_Product_Detail extends Controller_Abstract
{
    public function execute()
    {
        $urlKey = $this->getRequest('key'); // Assuming index.php passes 'key'

        $product = new Model_Product();
        $product->loadByUrlKey($urlKey);

        if (!$product->getData('entity_id')) {
            header("HTTP/1.0 404 Not Found");
            echo "Product not found.";
            return;
        }

        $view = new View_Product();
        $view->setTemplate('product/detail');
        $view->debug_controller = 'Controller_Product_Detail_New';
        $view->product = $product->getData();
        $view->title = $product->getData('name');
        $view->page_css = 'product-detail.css';

        echo $view->toHtml();
    }
}
