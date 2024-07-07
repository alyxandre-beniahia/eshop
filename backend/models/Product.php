<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $brand;
    public $description;
    public $price;
    public $discount_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, brand=:brand, description=:description, price=:price, discount_id=:discount_id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":brand", $this->brand);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":discount_id", $this->discount_id);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    function readById() {
        $query = "SELECT p.*, d.discount_percent,
                         IF(d.discount_percent IS NULL, p.price, p.price * (1 - d.discount_percent / 100)) AS discounted_price,
                         (SELECT GROUP_CONCAT(pi.image_path SEPARATOR ',')
                          FROM product_images pi
                          WHERE pi.product_id = p.id) AS images
                  FROM " . $this->table_name . " p
                  LEFT JOIN discounts d ON p.discount_id = d.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            return $stmt;
        }
    
        return null;
    }
    
    
    

    function read() {
        $query = "SELECT p.*, d.discount_percent,
                         IF(d.discount_percent IS NULL, p.price, p.price * (1 - d.discount_percent / 100)) AS discounted_price,
                         (SELECT GROUP_CONCAT(pi.image_path)
                          FROM product_images pi
                          WHERE pi.product_id = p.id) AS images
                  FROM " . $this->table_name . " p
                  LEFT JOIN discounts d ON p.discount_id = d.id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt;
    }
    
    

    function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, brand=:brand, description=:description, price=:price, discount_id=:discount_id WHERE id=:id";
        // Remove 'size_id=:size_id,' from the SET clause
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
    
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":brand", $this->brand);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":discount_id", $this->discount_id);
        // Remove the line that binds the :size_id parameter
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
      
    
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->id = htmlspecialchars(strip_tags($this->id));
    
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            // Delete associated stock records
            $stock = new Stock($this->conn);
            $stock->deleteProductStock($this->id);
    
            return true;
        }
    
        return false;
    }    

    function addCategory($categoryId, $productId) {
        $query = "INSERT INTO product_category_mapping (product_id, category_id) VALUES (:product_id, :category_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $productId);
        $stmt->bindParam(":category_id", $categoryId);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }    
    
    function getCategories() {
        $query = "SELECT pc.* FROM product_categories pc
                  JOIN product_category_mapping pcm ON pc.id = pcm.category_id
                  WHERE pcm.product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    private function sanitize() {
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->discount_id = htmlspecialchars(strip_tags($this->discount_id));
        // Remove the line that sanitizes $this->size_id
    }
    

    function getStock() {
        $query = "SELECT s.id, s.quantity, sz.id as size_id, sz.name as size_name
                  FROM " . $this->table_name . " p
                  JOIN Stock s ON p.id = s.product_id
                  JOIN Sizes sz ON s.size_id = sz.id
                  WHERE p.id = :product_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->execute();
    
        $stockInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Restructure the stock information
        $restructuredStock = array();
        foreach ($stockInfo as $stock) {
            $restructuredStock[] = array(
                'id' => $stock['id'],
                'quantity' => $stock['quantity'],
                'size_id' => $stock['size_id'],
                'size_name' => $stock['size_name']
            );
        }
    
        return $restructuredStock;
    }
    
    
    
    
}
?>
