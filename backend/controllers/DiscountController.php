<?php
include_once '../config/db.php';
include_once '../models/Discount.php';

class DiscountController {
    private $db;
    private $discount;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->discount = new Discount($this->db);
    }

    public function index() {
        $stmt = $this->discount->read();
        $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($discounts);
    }

    public function create($data) {
        $this->discount->name = $data['name'];
        $this->discount->description = $data['description'];
        $this->discount->discount_percent = $data['discount_percent'];
        $this->discount->active = $data['active'];

        if ($this->discount->create()) {
            echo json_encode(array("message" => "Discount created."));
        } else {
            echo json_encode(array("message" => "Discount could not be created."));
        }
    }

    public function update($data) {
        $this->discount->id = $data['id'];
        $this->discount->name = $data['name'];
        $this->discount->description = $data['description'];
        $this->discount->discount_percent = $data['discount_percent'];
        $this->discount->active = $data['active'];

        if ($this->discount->update()) {
            echo json_encode(array("message" => "Discount updated."));
        } else {
            echo json_encode(array("message" => "Discount could not be updated."));
        }
    }

    public function delete($id) {
        $this->discount->id = $id;

        if ($this->discount->delete()) {
            echo json_encode(array("message" => "Discount deleted."));
        } else {
            echo json_encode(array("message" => "Discount could not be deleted."));
        }
    }
}
?>
