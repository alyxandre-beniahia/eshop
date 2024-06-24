<?php
include_once '../config/db.php';
include_once '../models/Product.php';

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }

    public function index() {
        $stmt = $this->product->read();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    }

    public function create($data) {
        $this->product->name = $data['name'];
        $this->product->brand = $data['brand'];
        $this->product->description = $data['description'];
        $this->product->price = $data['price'];
        $this->product->discount_id = $data['discount_id'];
        $this->product->size_id = $data['size_id'];

        if ($this->product->create()) {
            echo json_encode(array("message" => "Product created."));
        } else {
            echo json_encode(array("message" => "Product could not be created."));
        }
    }

    public function update($data) {
        $this->product->id = $data['id'];
        $this->product->name = $data['name'];
        $this->product->brand = $data['brand'];
        $this->product->description = $data['description'];
        $this->product->price = $data['price'];
        $this->product->discount_id = $data['discount_id'];
        $this->product->size_id = $data['size_id'];

        if ($this->product->update()) {
            echo json_encode(array("message" => "Product updated."));
        } else {
            echo json_encode(array("message" => "Product could not be updated."));
        }
    }

    public function delete($id) {
        $this->product->id = $id;

        if ($this->product->delete()) {
            echo json_encode(array("message" => "Product deleted."));
        } else {
            echo json_encode(array("message" => "Product could not be deleted."));
        }
    }
}
?>
