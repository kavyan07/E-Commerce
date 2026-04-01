<?php

abstract class Model_Abstract
{
    protected $_resource = null;
    protected $_data = [];

    public function __construct($data = [])
    {
        $this->_data = $data;
    }

    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }
    public function getData($key = null)
    {
        if ($key === null)
            return $this->_data;
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function load($value, $column = null)
    {
        if ($this->_resource) {
            $data = $this->_resource->load($value, $column);
            if ($data) {
                $this->setData($data);
            }
        }
        return $this;
    }

    public function save()
    {
        if ($this->_resource) {
            $id = $this->_resource->save($this->_data);
            if ($id) {
                // For SERIAL/ID updates
                $pk = $this->_resource->getPrimaryKey();
                if (!isset($this->_data[$pk])) {
                    $this->_data[$pk] = $id;
                }
            }
        }
        return $this;
    }

    public function delete()
    {
        if ($this->_resource && isset($this->_data[$this->_resource->getPrimaryKey()])) {
            return $this->_resource->delete([
                $this->_resource->getPrimaryKey() => $this->_data[$this->_resource->getPrimaryKey()]
            ]);
        }
        return false;
    }

    /**
     * Magic methods for camelCase getters and setters
     */
    public function __call($name, $args)
    {
        $prefix = substr($name, 0, 3);
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', substr($name, 3)));

        if ($prefix == 'get') {
            return $this->getData($key);
        } elseif ($prefix == 'set') {
            $this->_data[$key] = $args[0];
            return $this;
        } elseif ($prefix == 'uns') {
            unset($this->_data[$key]);
            return $this;
        } elseif ($prefix == 'has') {
            return isset($this->_data[$key]);
        }

        throw new Exception("Method $name not found in " . get_class($this));
    }

    public function addData($data)
    {
        $this->_data = array_merge($this->_data, $data);
        return $this;
    }
}
