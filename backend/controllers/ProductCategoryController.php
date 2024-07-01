<?php
include_once '../config/db.php';
include_once '../models/ProductCategory.php';

class ProductCategoryController {
    private $db;
    private $productCategory;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productCategory = new ProductCategory($this->db);
    }

    public function index() {
        $categories = $this->productCategory->read();
        echo json_encode($categories);
    }

    public function getById($id) {
        $category = $this->productCategory->getById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            echo json_encode(array("message" => "Category not found."));
        }
    }

    public function create($data) {
        $this->productCategory->name = $data['name'];
        $this->productCategory->description = $data['description'];

        if ($this->productCategory->create()) {
            echo json_encode(array("message" => "Category created successfully."));
        } else {
            echo json_encode(array("message" => "Category could not be created."));
        }
    }

    public function update($data) {
        $this->productCategory->id = $data['id'];
        $this->productCategory->name = $data['name'];
        $this->productCategory->description = $data['description'];

        if ($this->productCategory->update()) {
            echo json_encode(array("message" => "Category updated successfully."));
        } else {
            echo json_encode(array("message" => "Category could not be updated."));
        }
    }

    public function delete($id) {
        $this->productCategory->id = $id;
        if ($this->productCategory->delete()) {
            echo json_encode(array("message" => "Category deleted successfully."));
        } else {
            echo json_encode(array("message" => "Category could not be deleted."));
        }
    }
}
?>
