<?php
class OrderItems {
    private $conn;
    private $table_name = "ORDER_ITEMS";

    public $id;
    public $order_id;
    public $product_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET order_id=:order_id, product_id=:product_id, quantity=:quantity, created_at=:created_at, modified_at=:modified_at";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);

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
        $query = "UPDATE " . $this->table_name . " SET order_id=:order_id, product_id=:product_id, quantity=:quantity, created_at=:created_at, modified_at=:modified_at WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);

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
        $this->order_id = isset($this->order_id) ? htmlspecialchars(strip_tags($this->order_id)) : null;
        $this->product_id = isset($this->product_id) ? htmlspecialchars(strip_tags($this->product_id)) : null;
        $this->quantity = isset($this->quantity) ? htmlspecialchars(strip_tags($this->quantity)) : null;
    }

    function getOrderDetails() {
        $query = "SELECT * FROM order_details WHERE id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>