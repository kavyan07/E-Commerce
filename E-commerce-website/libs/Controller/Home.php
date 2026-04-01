<?php

class Controller_Home extends Controller_Abstract
{
    public function execute()
    {
        $productCollection = new Model_Product_Collection();
        $products = $productCollection->load();

        $view = new View_Default();
        $view->setTemplate('home/main');
        $view->products = $products;
        $view->title = 'Welcome to EasyCart';

        echo $view->toHtml();
    }
}
