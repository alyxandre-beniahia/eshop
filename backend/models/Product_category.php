<?php
class ProductCategory {
    private $conn;
    private $table_name = "product_categories";
    public $id;
    public $name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description, created_at=NOW(), updated_at=NOW()";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }    

    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, updated_at=NOW() WHERE id=:id";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
    
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
    
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
        $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
    }

    function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
