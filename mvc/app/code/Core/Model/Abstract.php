<?php
class Core_Model_Abstract
{
    protected $_data = [];
    public function __construct()
    {

    }
    public function __set($name, $value)
    {
       $this->_data[$name] = $value;
       
    }
    public function __get($name)
    {
      return $this->_data[$name];
    }
    public function addData($data = [])
    {
          $this->_data =$data;
          return $this;
    }

}




?>