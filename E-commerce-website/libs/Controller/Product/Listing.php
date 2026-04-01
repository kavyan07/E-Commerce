<?php

class Controller_Product_Listing extends Controller_Abstract
{
    public function execute()
    {
        $productCollection = new Model_Product_Collection();

        // Handling basic search/filter through request
        $keyword = $this->getRequest('q');
        if ($keyword) {
            $productCollection->addFilter('name', "%$keyword%", 'LIKE');
        }

        $products = $productCollection->load();

        $view = new View_Default();
        $view->setTemplate('product/listing');
        $view->productList = $products;
        $view->title = 'Explore Products';
        $view->page_title = 'Explore All Products - EasyCart';
        $view->page_css = 'product-listing.css';

        echo $view->toHtml();
    }
}
