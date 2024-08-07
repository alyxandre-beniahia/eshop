<?php
class Size {
    private $conn;
    private $table_name = "sizes";

    public $id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all sizes
    function read() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Create a new size
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name";
    
        $stmt = $this->conn->prepare($query);
    
        // Validate the size value against the ENUM options
        $validSizes = array('XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL');
        if (in_array($this->name, $validSizes)) {
            $stmt->bindParam(":name", $this->name);
    
            if ($stmt->execute()) {
                return true;
            }
        }
    
        return false;
    }
    //update a size
    function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Validate the size value against the ENUM options
        $validSizes = array('XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL');
        if (in_array($this->name, $validSizes)) {
            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":name", $this->name);

            if ($stmt->execute()) {
                return true;
            }
        }

        return false;
    }

    // Delete a size
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

}
?>
