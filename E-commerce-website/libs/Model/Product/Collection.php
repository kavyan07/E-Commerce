<?php

class Model_Product_Collection extends Core_Model_Resource_Collection_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setResource(new Model_Product_Resource());
    }

    /**
     * Optimized Join with Categories
     */
    public function addCategoryFilter($categoryId)
    {
        $this->addJoin('catalog_category_products', 'main.entity_id = ccp.product_id');
        $this->addFilter('category_id', $categoryId);
        return $this;
    }
}
