<?php
class PaymentDetails {
    private $conn;
    private $table_name = "payment_details";
    public $id;
    public $order_id;
    public $amount;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET order_id=:order_id, amount=:amount, status=:status, created_at=:created_at, modified_at=:modified_at";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindValue(":modified_at", date('Y-m-d H:i:s'));

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
        $query = "UPDATE " . $this->table_name . " SETorder_id=:order_id, amount=:amount, status=:status, created_at=:created_at, modified_at=:modified_at WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);

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
        $this->amount = isset($this->amount) ? htmlspecialchars(strip_tags($this->amount)) : null;
        $this->status = isset($this->status) ? htmlspecialchars(strip_tags($this->status)) : null;
    }

    function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getByOrderId($orderId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
