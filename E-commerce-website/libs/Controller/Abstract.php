<?php

abstract class Controller_Abstract
{
    protected $_request = null;

    public function __construct()
    {
        // Simple request object wrapper could be added here
        $this->_request = $_REQUEST;
    }
      
    public function getRequest($key = null)
    {
        if ($key === null)
            return $this->_request;
        return isset($this->_request[$key]) ? $this->_request[$key] : null;
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    abstract public function execute();
}
