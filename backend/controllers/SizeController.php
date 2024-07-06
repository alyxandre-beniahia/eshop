<?php
include_once '../config/db.php';
include_once '../models/Size.php';

class SizeController {
    private $db;
    private $size;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->size = new Size($this->db);
    }

    public function index() {
        $stmt = $this->size->read();
        $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("data" => $sizes));
    }

    public function create($data) {
        $this->size->name = $data['name'];

        if ($this->size->create()) {
            echo json_encode(array("message" => "Size created."));
        } else {
            echo json_encode(array("message" => "Size could not be created."));
        }
    }

    public function update($data) {
        $this->size->id = $data['id'];
        $this->size->name = $data['name'];

        if ($this->size->update()) {
            echo json_encode(array("message" => "Size updated."));
        } else {
            echo json_encode(array("message" => "Size could not be updated."));
        }
    }

    public function delete($id) {
        $this->size->id = $id;

        if ($this->size->delete()) {
            echo json_encode(array("message" => "Size deleted."));
        } else {
            echo json_encode(array("message" => "Size could not be deleted."));
        }
    }
}
?>
