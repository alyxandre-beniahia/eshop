<?php
include_once '../config/db.php';
include_once '../models/Product.php';

class ProductController {
    private $db;
    private $product;
    private $id;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->id = null;
    }

    public function index() {
        $stmt = $this->product->read();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = array("products" => $products);
        $this->sendResponse($response);
    }
    
    public function create($data) {
        $validatedData = $this->validateProductData($data);
    
        if ($validatedData === null) {
            $response = array("message" => "Required fields cannot be empty.");
            $this->sendResponse($response);
            return;
        }
    
        $this->product->name = $validatedData['name'];
        $this->product->brand = $validatedData['brand'];
        $this->product->description = $validatedData['description'];
        $this->product->price = $validatedData['price'];
        $this->product->discount_id = $validatedData['discount_id'];
        $this->product->size_id = $validatedData['size_id'];
    
        if ($this->product->create()) {
            $response = array("message" => "Product created.");
        } else {
            $response = array("message" => "Product could not be created.");
        }
        $this->sendResponse($response);
    }

    public function update($data) {
        // Validate input data
        $this->product->id = isset($data['id']) ? $data['id'] : null;
        $this->product->name = isset($data['name']) ? $data['name'] : null;
        $this->product->brand = isset($data['brand']) ? $data['brand'] : null;
        $this->product->description = isset($data['description']) ? $data['description'] : null;
        $this->product->price = isset($data['price']) ? $data['price'] : null;
        $this->product->discount_id = isset($data['discount_id']) ? $data['discount_id'] : 0;
        $this->product->size_id = isset($data['size_id']) ? $data['size_id'] : 0;
    
        // Check if any of the required fields are empty
        if (is_null($this->product->id) || is_null($this->product->name) || is_null($this->product->brand) || is_null($this->product->description) || is_null($this->product->price)) {
            $response = array("message" => "Required fields cannot be empty.");
            $this->sendResponse($response, 400); // Bad Request
            return;
        }
    
        // Debug: Print the product data
        echo "Updating product with data: ";
        echo "<pre>";
        print_r($this->product);
        echo "</pre>";
    
        if ($this->product->update()) {
            $response = array("message" => "Product updated.");
            $this->sendResponse($response, 200); // OK
        } else {
            $response = array("message" => "Product could not be updated.");
            $this->sendResponse($response, 500); // Internal Server Error
        }
    }
    

    public function delete($id) {
        $this->product->id = $id;
    
        if ($this->product->delete()) {
            $response = array("message" => "Product deleted.");
        } else {
            $response = array("message" => "Product could not be deleted.");
        }
    
        $this->sendResponse($response);
    }
    
    private function validateProductData($data) {
        $validatedData = array();
    
        // Validate name
        $validatedData['name'] = isset($data['name']) && is_string($data['name']) ? trim($data['name']) : null;
    
        // Validate brand
        $validatedData['brand'] = isset($data['brand']) && is_string($data['brand']) ? trim($data['brand']) : null;
    
        // Validate description
        $validatedData['description'] = isset($data['description']) && is_string($data['description']) ? trim($data['description']) : null;
    
        // Validate price
        $validatedData['price'] = isset($data['price']) && is_numeric($data['price']) ? (float) $data['price'] : null;
    
        // Validate discount_id
        $validatedData['discount_id'] = isset($data['discount_id']) && !empty($data['discount_id']) ? $data['discount_id'] : 0;
    
        // Validate size_id
        $validatedData['size_id'] = isset($data['size_id']) && !empty($data['size_id']) ? $data['size_id'] : 0;
    
        // Check if any of the required fields are empty or invalid
        if (is_null($validatedData['name']) || is_null($validatedData['brand']) || is_null($validatedData['description']) || is_null($validatedData['price'])) {
            return null;
        }
    
        return $validatedData;
    }
    
    

    private function sendResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>
