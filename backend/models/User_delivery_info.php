<?php
class UserDeliveryInfo {
    private $conn;
    private $table_name = "user_delivery_info";
    public $id;
    public $address_line1;
    public $address_line2;
    public $city;
    public $postal_code;
    public $country;
    public $telephone;
    public $mobile;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET address_line1=:address_line1, address_line2=:address_line2, city=:city, postal_code=:postal_code, country=:country, telephone=:telephone, mobile=:mobile";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":address_line1", $this->address_line1);
        $stmt->bindParam(":address_line2", $this->address_line2);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":postal_code", $this->postal_code);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":mobile", $this->mobile);

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
        $query = "UPDATE " . $this->table_name . " SET address_line1=:address_line1, address_line2=:address_line2, city=:city, postal_code=:postal_code, country=:country, telephone=:telephone, mobile=:mobile WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":address_line1", $this->address_line1);
        $stmt->bindParam(":address_line2", $this->address_line2);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":postal_code", $this->postal_code);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":mobile", $this->mobile);

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
        $this->address_line1 = isset($this->address_line1) ? htmlspecialchars(strip_tags($this->address_line1)) : null;
        $this->address_line2 = isset($this->address_line2) ? htmlspecialchars(strip_tags($this->address_line2)) : null;
        $this->city = isset($this->city) ? htmlspecialchars(strip_tags($this->city)) : null;
        $this->postal_code = isset($this->postal_code) ? htmlspecialchars(strip_tags($this->postal_code)) : null;
        $this->country = isset($this->country) ? htmlspecialchars(strip_tags($this->country)) : null;
        $this->telephone = isset($this->telephone) ? htmlspecialchars(strip_tags($this->telephone)) : null;
        $this->mobile = isset($this->mobile) ? htmlspecialchars(strip_tags($this->mobile)) : null;
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