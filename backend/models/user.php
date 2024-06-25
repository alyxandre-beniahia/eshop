<?php
class User {
    public $id;
    public $username;
    public $password;
    public $email;
    public $firstname;
    public $lastname;
    public $role;
    public $created_at;
    public $modified_at;

    public function create() {
        $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

        $query = "INSERT INTO users (username, password, email, firstname, lastname, role, created_at, modified_at) 
                  VALUES (:username, :password, :email, :firstname, :lastname, :role, :created_at, :modified_at)";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':modified_at', $this->modified_at);

        $stmt->execute();
    }

    public function read($id) {
        $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $pdo->prepare($sql);

        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? new self($result) : null;
    }

    public function update() {
        $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

        $sql = "UPDATE users 
                SET username = :username, 
                    password = :password, 
                    email = :email, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    role = :role, 
                    modified_at = NOW() 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':role', $this->role);

        $stmt->execute();
    }

    public function delete() {
        $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

        $sql = "DELETE FROM users WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();
    }
}
