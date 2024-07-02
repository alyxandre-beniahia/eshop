<?php
class ProductImages {
    private $conn;
    private $table_name = "IMAGE";

    public $id;
    public $product_id;
    public $image_path;
    public $is_primary;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    product_id = :product_id,
                    image_path = :image_path,
                    is_primary = :is_primary";

        $stmt = $this->conn->prepare($query);

        $this->image_path = htmlspecialchars(strip_tags($this->image_path));
        $this->is_primary = htmlspecialchars(strip_tags($this->is_primary));

        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":image_path", $this->image_path);
        $stmt->bindParam(":is_primary", $this->is_primary);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->product_id);
        $stmt->execute();
        return $stmt;
    }

    function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    image_path = :image_path,
                    is_primary = :is_primary
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->image_path = htmlspecialchars(strip_tags($this->image_path));
        $this->is_primary = htmlspecialchars(strip_tags($this->is_primary));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":image_path", $this->image_path);
        $stmt->bindParam(":is_primary", $this->is_primary);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
