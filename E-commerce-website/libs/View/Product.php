<?php

class View_Product extends View_Abstract
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setTemplate('product/listing');
    }

    // Custom logic for product view can go here
}
