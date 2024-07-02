<?php
include_once '../config/db.php';
include_once '../models/User.php';
include_once '../middleware/auth.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function index() {
        $stmt = $this->user->read();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($users);
    }

    public function create($data) {
        $this->user->username = $data['username'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->lastname = $data['lastname'];
        $this->user->firstname = $data['firstname'];
        $this->user->role = $data['role'];


        if ($this->user->create()) {
            echo json_encode(array("message" => "User created."));
        } else {
            echo json_encode(array("message" => "User could not be created."));
        }
    }

    public function update($data) {
        $this->user->id = $data['id'];
        $this->user->username = $data['username'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->lastname = $data['lastname'];
        $this->user->firstname = $data['firstname'];
        $this->user->role = $data['role'];


        if ($this->user->update()) {
            echo json_encode(array("message" => "User updated."));
        } else {
            echo json_encode(array("message" => "User could not be updated."));
        }
    }

    public function delete($id) {
        $this->user->id = $id;

        if ($this->user->delete()) {
            echo json_encode(array("message" => "User deleted."));
        } else {
            echo json_encode(array("message" => "User could not be deleted."));
        }
    }

    // authenticate user /login
    public function authenticate($data) {
        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? $data['password'] : null;
    
        if ($email === null || $password === null) {
            echo json_encode(array("message" => "Email and password are required."));
            return;
        }
    
        $user = $this->user->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $userId = $user['id'];
            $userRole = $user['role'];
            $secret_key = "betterstackedthensticked"; // Define the secret key here
            $jwt = generateJWT($userId, $userRole, $secret_key);
    
            echo json_encode(array("token" => $jwt));
        } else {
            echo json_encode(array("message" => "Invalid email or password."));
        }
    }
    
}
?>
