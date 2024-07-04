<?php
include_once '../config/db.php';
include_once '../models/Product_category.php';

class ProductCategoryController {
    private $db;
    private $productCategory;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->productCategory = new ProductCategory($this->db);
    }

    public function index() {
        $stmt = $this->productCategory->read();
        if ($stmt !== null) {
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = array("products" => $products);
            $this->sendResponse($response);
        } else {
            $response = array("message" => "Error retrieving products");
            $this->sendResponse($response, 500);
        }
    }
    

    public function getById($id) {
        $category = $this->productCategory->getById($id);
        if ($category) {
            try {
                echo json_encode($category);
            } catch (Exception $e) {
                echo json_encode(array("message" => "Error encoding category data as JSON."));
            }
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

    public function update($data, $id) {
        $this->productCategory->id = $id;
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

    public function addProduct($categoryId, $productId) {
        $this->productCategory->id = $categoryId;
        if ($this->productCategory->addProduct($productId)) {
            echo json_encode(array("message" => "Product added to category successfully."));
        } else {
            echo json_encode(array("message" => "Failed to add product to category."));
        }
    }

    public function getProductsByCategory($categoryId) {
        $this->productCategory->id = $categoryId;
        $stmt = $this->productCategory->getProducts();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($products) {
            try {
                $response = array("products" => $products);
                $this->sendResponse($response);
            } catch (Exception $e) {
                $response = array("message" => "Error encoding products as JSON.");
                echo json_encode($response);
            }
        } else {
            echo json_encode(array("message" => "No products found for this category."));
        }
    }    
    

    public function updateProducts($productIds) {
        $this->productCategory->id = $_GET['id'];
        $this->productCategory->removeAllProducts();
        foreach ($productIds as $productId) {
            $this->productCategory->addProduct($productId);
        }
        echo json_encode(array("message" => "Category products updated successfully."));
    }

    private function sendResponse($response, $statusCode = 200, $errorMessage = null) {
        http_response_code($statusCode);
        if ($errorMessage !== null) {
            $response['error'] = $errorMessage;
        }
        echo json_encode($response);
    }
}
?>
