<?php

abstract class Core_Model_Resource_Collection_Abstract
{
    protected $_resource = null;
    protected $_select = null;
    protected $_joins = [];
    protected $_filters = [];

    public function __construct()
    {
        $this->_select = new Core_Resource_Query();
    }

    public function setResource($resource)
    {
        $this->_resource = $resource;
        return $this;
    }

    public function addJoin($table, $condition, $cols = '*')
    {
        $this->_joins[] = ['table' => $table, 'cond' => $condition, 'cols' => $cols];
        return $this;
    }

    public function addFilter($field, $value, $op = '=')
    {
        $this->_filters[] = ['field' => $field, 'value' => $value, 'op' => $op];
        return $this;
    }

    /**
     * Optimized Load: Builds the complex SQL string and executes it
     */
    public function load()
    {
        $table = $this->_resource->getTableName();
        $this->_select->select($table);

        // This is where common joins and filters are applied dynamically
        $sql = (string) $this->_select; // Triggers __toString() builder

        // Add filters to SQL (simplified for demo)
        if (!empty($this->_filters)) {
            $sql .= " WHERE ";
            $clauses = [];
            foreach ($this->_filters as $f) {
                $clauses[] = "{$f['field']} {$f['op']} '{$f['value']}'";
            }
            $sql .= implode(' AND ', $clauses);
        }

        $db = Core_Connection::getInstance();
        return $db->query($sql)->fetchAll();
    }
}
