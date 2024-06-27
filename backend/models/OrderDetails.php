<?php
class OrderDetails {
    private $conn;
    private $table_name = "order_details";
    public $id;
    public $customer_id;
    public $status;
    public $total;
    public $created_at;
    public $modified_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET customer_id=:customer_id, status=:status, total=:total, created_at=:created_at, modified_at=:modified_at";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":modified_at", $this->modified_at);

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
        $query = "UPDATE " . $this->table_name . " SET customer_id=:customer_id, status=:status, total=:total, created_at=:created_at, modified_at=:modified_at WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":modified_at", $this->modified_at);

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
        $this->customer_id = isset($this->customer_id) ? htmlspecialchars(strip_tags($this->customer_id)) : null;
        $this->status = isset($this->status) ? htmlspecialchars(strip_tags($this->status)) : null;
        $this->total = isset($this->total) ? htmlspecialchars(strip_tags($this->total)) : null;
        $this->created_at = isset($this->created_at) ? htmlspecialchars(strip_tags($this->created_at)) : null;
        $this->modified_at = isset($this->modified_at) ? htmlspecialchars(strip_tags($this->modified_at)) : null;
    }

    function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getOrderItems() {
        $query = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
