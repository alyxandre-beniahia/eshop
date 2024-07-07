<?php
class Stock {
    private $conn;
    private $table_name = "stock";

    public $id;
    public $product_id;
    public $size_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    function readBySize($size_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE size_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $size_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function update($size_id, $quantity) {
        $query = "UPDATE " . $this->table_name . " SET quantity = ? WHERE size_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quantity);
        $stmt->bindParam(2, $size_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function updateProductStock($product_id, $size_id, $quantity) {
        $query = "UPDATE " . $this->table_name . "
                  SET quantity = :quantity
                  WHERE product_id = :product_id AND size_id = :size_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":size_id", $size_id);
    
        return $stmt->execute();
    }
    
    function insertProductStock($product_id, $size_id, $quantity) {
        $query = "INSERT INTO " . $this->table_name . "
                  (product_id, size_id, quantity)
                  VALUES (:product_id, :size_id, :quantity)";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":size_id", $size_id);
        $stmt->bindParam(":quantity", $quantity);
    
        return $stmt->execute();
    }

    function deleteProductStock($productId) {
    $query = "DELETE FROM " . $this->table_name . " WHERE product_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $productId);

    return $stmt->execute();
}

    

}
?>