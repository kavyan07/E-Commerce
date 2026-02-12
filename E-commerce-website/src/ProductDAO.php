<?php
/**
 * Product Data Access Object (Advanced Schema)
 */
require_once __DIR__ . '/../includes/db.php';

class ProductDAO
{
    private $db;

    public function __construct()
    {
        $this->db = getDb();
    }

    /**
     * Get all products with their categories and brands
     */
    public function getAllProducts()
    {
        $sql = "SELECT p.entity_id as id, p.name, p.description, p.price, p.original_price, 
                       p.image_main, p.badge, p.shipping_type,
                       c.name as category_name, c.slug as category_slug, b.name as brand_name
                FROM catalog_product_entity p
                LEFT JOIN catalog_category_products ccp ON p.entity_id = ccp.product_id
                LEFT JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
                LEFT JOIN brands b ON p.brand_id = b.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get product details including attributes (color, size, etc.)
     */
    public function getProductById($id)
    {
        // Main details
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name
                FROM catalog_product_entity p
                LEFT JOIN catalog_category_products ccp ON p.entity_id = ccp.product_id
                LEFT JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.entity_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Fetch attributes
            $attrSql = "SELECT attribute_code, value FROM catalog_product_attribute WHERE product_id = ?";
            $attrStmt = $this->db->prepare($attrSql);
            $attrStmt->execute([$id]);
            $product['attributes'] = $attrStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $product;
    }

    public function getCategories()
    {
        $stmt = $this->db->query("SELECT * FROM catalog_category_entity");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get products in a specific category
     */
    public function getProductsByCategory($slug)
    {
        $sql = "SELECT p.entity_id as id, p.name, p.description, p.price, p.original_price, 
                       p.image_main, p.badge, p.shipping_type,
                       c.name as category_name, c.slug as category_slug, b.name as brand_name
                FROM catalog_product_entity p
                JOIN catalog_category_products ccp ON p.entity_id = ccp.product_id
                JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE c.slug = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
