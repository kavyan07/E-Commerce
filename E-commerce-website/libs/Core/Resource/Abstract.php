<?php

abstract class Core_Resource_Abstract
{
    protected $_tableName = '';
    protected $_primaryKey = '';
    protected $_db = null;

    public function __construct()
    {
        $this->_db = Core_Connection::getInstance();
    }

    public function getTableName()
    {
        return $this->_tableName;
    }
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    public function load($value, $column = null)
    {
        $column = $column ?: $this->_primaryKey;
        $sql = "SELECT * FROM {$this->_tableName} WHERE {$column} = :val LIMIT 1";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([':val' => $value]);
        return $stmt->fetch();
    }

    public function save($data)
    {
        if (isset($data[$this->_primaryKey]) && $data[$this->_primaryKey]) {
            return $this->update($data, [$this->_primaryKey => $data[$this->_primaryKey]]);
        } else {
            return $this->insert($data);
        }
    }

    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->_tableName} ({$columns}) VALUES ({$placeholders}) RETURNING {$this->_primaryKey}";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($this->_prepareData($data));
        return $stmt->fetchColumn();
    }

    public function update($data, $where)
    {
        $set = [];
        foreach ($data as $col => $val) {
            $set[] = "{$col} = :s_{$col}";
        }
        $whereClause = [];
        foreach ($where as $col => $val) {
            $whereClause[] = "{$col} = :w_{$col}";
        }

        $sql = "UPDATE {$this->_tableName} SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $whereClause);
        $stmt = $this->_db->prepare($sql);

        $params = [];
        foreach ($data as $col => $val)
            $params[":s_{$col}"] = $val;
        foreach ($where as $col => $val)
            $params[":w_{$col}"] = $val;

        return $stmt->execute($params);
    }

    public function delete($where)
    {
        $whereClause = [];
        foreach ($where as $col => $val) {
            $whereClause[] = "{$col} = :w_{$col}";
        }
        $sql = "DELETE FROM {$this->_tableName} WHERE " . implode(' AND ', $whereClause);
        $stmt = $this->_db->prepare($sql);

        $params = [];
        foreach ($where as $col => $val)
            $params[":w_{$col}"] = $val;

        return $stmt->execute($params);
    }

    protected function _prepareData($data)
    {
        $prepared = [];
        foreach ($data as $key => $val) {
            $prepared[':' . $key] = $val;
        }
        return $prepared;
    }
}
