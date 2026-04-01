<?php

class Model_Product extends Model_Abstract
{
    public function __construct($data = [])
    {
        $this->_resource = new Model_Product_Resource();
        parent::__construct($data);
    }

    /**
     * Load product by SKU or URL Key (dynamic URL)
     */
    public function loadByUrlKey($urlKey)
    {
        $data = $this->_resource->load($urlKey, 'url_key');
        if ($data) {
            $this->setData($data);
        }
        return $this;
    }
}
