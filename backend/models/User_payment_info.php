<?php
class UserPaymentInfo {
    private $conn;
    private $table_name = "user_payment_info";
    public $id;
    public $credit_card_number;
    public $cryptogram;
    public $expiration_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET credit_card_number=:credit_card_number, cryptogram=:cryptogram, expiration_date=:expiration_date";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":credit_card_number", $this->credit_card_number);
        $stmt->bindParam(":cryptogram", $this->cryptogram);
        $stmt->bindParam(":expiration_date", $this->expiration_date);

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
        $query = "UPDATE " . $this->table_name . " SET credit_card_number=:credit_card_number, cryptogram=:cryptogram, expiration_date=:expiration_date WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":credit_card_number", $this->credit_card_number);
        $stmt->bindParam(":cryptogram", $this->cryptogram);
        $stmt->bindParam(":expiration_date", $this->expiration_date);

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
        $this->credit_card_number = isset($this->credit_card_number) ? htmlspecialchars(strip_tags($this->credit_card_number)) : null;
        $this->cryptogram = isset($this->cryptogram) ? htmlspecialchars(strip_tags($this->cryptogram)) : null;
        $this->expiration_date = isset($this->expiration_date) ? htmlspecialchars(strip_tags($this->expiration_date)) : null;
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