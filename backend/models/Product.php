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
    public $size_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, brand=:brand, description=:description, price=:price, discount_id=:discount_id, size_id=:size_id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":brand", $this->brand);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":discount_id", $this->discount_id);
        $stmt->bindParam(":size_id", $this->size_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        echo "Data retrieved from database";
        return $stmt;
    }
    

    function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, brand=:brand, description=:description, price=:price, discount_id=:discount_id, size_id=:size_id WHERE id=:id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
    
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":brand", $this->brand);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":discount_id", $this->discount_id);
        $stmt->bindParam(":size_id", $this->size_id);
    
        if ($stmt->execute()) {
            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $error = $stmt->errorInfo();
            echo "Error executing query: " . $error[2];
            return false;
        }
    }    
    
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    private function sanitize() {
        $this->id = isset($this->id) ? $this->id : null;
        $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
        $this->brand = isset($this->brand) ? htmlspecialchars(strip_tags($this->brand)) : null;
        $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
        $this->discount_id = isset($this->discount_id) ? htmlspecialchars(strip_tags($this->discount_id)) : null;
        $this->size_id = isset($this->size_id) ? htmlspecialchars(strip_tags($this->size_id)) : null;
    }
    
}
?>
