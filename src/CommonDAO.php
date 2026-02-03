<?php
/**
 * Category and Brand Data Access Objects
 */
require_once __DIR__ . '/../includes/db.php';

class CategoryDAO
{
    private $db;
    public function __construct()
    {
        $this->db = getDb();
    }

    public function getAllCategories()
    {
        return $this->db->query("SELECT * FROM catalog_category_entity ORDER BY name ASC")->fetchAll();
    }
}

class BrandDAO
{
    private $db;
    public function __construct()
    {
        $this->db = getDb();
    }

    public function getAllBrands()
    {
        return $this->db->query("SELECT * FROM brands ORDER BY name ASC")->fetchAll();
    }
}
