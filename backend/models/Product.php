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

    // Create a product
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

    // Read products
    function read() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Update a product
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
            return true;
        }

        return false;
    }

    // Delete a product
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
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->discount_id = htmlspecialchars(strip_tags($this->discount_id));
        $this->size_id = htmlspecialchars(strip_tags($this->size_id));
    }
}
?>
