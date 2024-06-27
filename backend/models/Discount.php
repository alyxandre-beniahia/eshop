<?php
class Discount {
    private $conn;
    private $table_name = "discounts";

    public $id;
    public $name;
    public $description;
    public $discount_percent;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new discount
    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    name = :name,
                    description = :description,
                    discount_percent = :discount_percent,
                    active = :active";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->discount_percent = htmlspecialchars(strip_tags($this->discount_percent));
        $this->active = htmlspecialchars(strip_tags($this->active));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":discount_percent", $this->discount_percent);
        $stmt->bindParam(":active", $this->active);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all discounts
    function read() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Update a discount
    function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    name = :name,
                    description = :description,
                    discount_percent = :discount_percent,
                    active = :active
                  WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->discount_percent = htmlspecialchars(strip_tags($this->discount_percent));
        $this->active = htmlspecialchars(strip_tags($this->active));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":discount_percent", $this->discount_percent);
        $stmt->bindParam(":active", $this->active);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a discount
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getDiscountPercentage($id) {
        $query = "SELECT discount_percent FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['discount_percent'];
    }
}
?>
