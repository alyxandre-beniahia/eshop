<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $firstname;
    public $lastname;
    public $role;
    public $created_at;
    public $modified_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, email=:email, password=:password, firstname=:firstname, lastname=:lastname, role=:role, created_at=NOW(), modified_at=NOW()";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read users
    function read() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Update a user
    function update() {
        $query = "UPDATE " . $this->table_name . " SET username=:username, email=:email, password=:password, firstname=:firstname, lastname=:lastname, role=:role, modified_at=NOW() WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->sanitize();

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a user
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

    // Authenticate a user
    function authenticate($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email=:email";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":email", $email);

        if ($stmt->execute()) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    private function sanitize() {
        $this->username = htmlspecialchars(strip_tags($this->username ?? ''));
        $this->email = htmlspecialchars(strip_tags($this->email ?? ''));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->firstname = htmlspecialchars(strip_tags($this->firstname ?? ''));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname ?? ''));
        $this->role = htmlspecialchars(strip_tags($this->role ?? ''));
    }
    
}
?>
