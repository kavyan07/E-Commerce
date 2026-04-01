<?php

class Core_Resource_Query
{
    protected $_sql = '';
    protected $_tableName = '';
    protected $_where = [];
    protected $_data = [];
    protected $_action = '';

    public function select($table)
    {
        $this->_action = 'SELECT';
        $this->_tableName = $table;
        return $this;
    }

    public function insert($table, $data)
    {
        $this->_action = 'INSERT';
        $this->_tableName = $table;
        $this->_data = $data;
        return $this;
    }

    public function update($table, $data)
    {
        $this->_action = 'UPDATE';
        $this->_tableName = $table;
        $this->_data = $data;
        return $this;
    }

    public function delete($table)
    {
        $this->_action = 'DELETE';
        $this->_tableName = $table;
        return $this;
    }

    public function where($where)
    {
        $this->_where = $where;
        return $this;
    }

    public function __toString()
    {
        switch ($this->_action) {
            case 'SELECT':
                $sql = "SELECT * FROM {$this->_tableName}";
                if (!empty($this->_where)) {
                    $sql .= " WHERE " . $this->_buildWhere();
                }
                return $sql;
            case 'INSERT':
                $cols = implode(',', array_keys($this->_data));
                $vals = "'" . implode("','", array_values($this->_data)) . "'";
                return "INSERT INTO {$this->_tableName} ({$cols}) VALUES ({$vals})";
            case 'UPDATE':
                $sets = [];
                foreach ($this->_data as $k => $v)
                    $sets[] = "$k = '$v'";
                $sql = "UPDATE {$this->_tableName} SET " . implode(',', $sets);
                if (!empty($this->_where)) {
                    $sql .= " WHERE " . $this->_buildWhere();
                }
                return $sql;
            case 'DELETE':
                $sql = "DELETE FROM {$this->_tableName}";
                if (!empty($this->_where)) {
                    $sql .= " WHERE " . $this->_buildWhere();
                }
                return $sql;
        }
        return '';
    }

    protected function _buildWhere()
    {
        $clauses = [];
        foreach ($this->_where as $k => $v) {
            $clauses[] = "$k = '$v'";
        }
        return implode(' AND ', $clauses);
    }

    /**
     * Optimized: Centralized Execution from the Query class
     */
    public function execute()
    {
        $db = Core_Connection::getInstance();
        $sql = $this->__toString();

        if ($this->_action === 'SELECT') {
            return $db->query($sql)->fetchAll();
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }
}
