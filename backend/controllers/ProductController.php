<?php
include_once '../config/db.php';
include_once '../models/Product.php';
include_once '../models/Stock.php';
include_once '../models/Image.php';
include_once '../models/Product_category.php';

class ProductController {
    private $db;
    private $product;
    private $stock;
    private $category;
    private $productImages;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->stock = new Stock($this->db);
        $this->category = new ProductCategory($this->db);
        $this->productImages = new ProductImages($this->db);
    }

    // get all products
    public function index() {
        $stmt = $this->product->read();
        if ($stmt !== null) {
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = array("products" => $products);
            $this->sendResponse($response);
        } else {
            $response = array("message" => "Error retrieving products");
            $this->sendResponse($response, 500);
        }
    }    
    

    // get specific product by id    
    public function getById($id) {
        $this->product->id = $id;
        $stmt = $this->product->readById();
    
        if ($stmt !== null) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $response = array("product" => $product);
                $this->sendResponse($response);
            } else {
                $response = array("message" => "Product not found.");
                $this->sendResponse($response, 404);
            }
        } else {
            $response = array("message" => "Error retrieving product.");
            $this->sendResponse($response, 500);
        }
    }
    
    // create new product
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
            $productId = $this->db->lastInsertId();

            // stock handling
            $sizeId = $this->product->size_id;
            $quantity = isset($data['quantity']) ? $data['quantity'] : 0;
            $this->stock->insertProductStock($productId, $sizeId, $quantity);

            // product images handling
            $this->handleProductImages($productId, isset($data['images']) ? $data['images'] : []);

            $response = array("message" => "Product created.");
        } else {
            $response = array("message" => "Product could not be created.");
        }
        $this->sendResponse($response);
    }
    

    public function update($data) {
        $this->product->id = isset($data['id']) ? $data['id'] : null;
        $this->product->name = isset($data['name']) ? $data['name'] : null;
        $this->product->brand = isset($data['brand']) ? $data['brand'] : null;
        $this->product->description = isset($data['description']) ? $data['description'] : null;
        $this->product->price = isset($data['price']) ? $data['price'] : null;
        $this->product->discount_id = isset($data['discount_id']) ? $data['discount_id'] : 0;
        $this->product->size_id = isset($data['size_id']) ? $data['size_id'] : 0;
    
        if (is_null($this->product->id) || is_null($this->product->name) || is_null($this->product->brand) || is_null($this->product->description) || is_null($this->product->price)) {
            $response = array("message" => "Required fields cannot be empty.");
            $this->sendResponse($response, 400);
            return;
        }
    
        if ($this->product->update()) {
            // Update stock quantity for the specified size
            $sizeId = $this->product->size_id;
            $quantity = isset($data['quantity']) ? $data['quantity'] : 0;
            $this->updateProductStock($this->product->id, $sizeId, $quantity);
    
            // Update product images
            $this->handleProductImages($this->product->id, isset($data['images']) ? $data['images'] : []);
    
            $response = array("message" => "Product updated.");
            $this->sendResponse($response, 200);
        } else {
            $response = array("message" => "Product could not be updated.");
            $errorMessage = "Product ID not found.";
            $this->sendResponse($response, 404, $errorMessage);
        }
    }
    

    private function updateProductStock($productId, $sizeId, $quantity) {
        $existingStock = $this->stock->readBySize($sizeId);

        if ($existingStock) {
            // Update existing stock quantity
            $this->stock->updateProductStock($productId, $sizeId, $quantity);
        } else {
            // Insert new stock entry
            $this->stock->insertProductStock($productId, $sizeId, $quantity);
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

    public function addCategory($categoryId, $productId= null) {
        if ($productId === null) {
            $productId = $this->product->id;
        }
        if ($this->product->addCategory($categoryId, $productId)) {
            $response = array("message" => "Category added to product successfully.");
        } else {
            $response = array("message" => "Failed to add category to product.");
        }
        $this->sendResponse($response);
    }

    public function getCategories($productId) {
        $this->product->id = $productId;
        $categories = $this->product->getCategories();
        if ($categories) {
            $response = array("categories" => $categories);
            $this->sendResponse($response);
        } else {
            $response = array("message" => "No categories found for this product.");
            $this->sendResponse($response);
        }
    }

    public function updateCategories($categoryIds) {
        $this->product->id = $_GET['id'];
        $this->product->removeAllCategories();
        foreach ($categoryIds as $categoryId) {
            $this->product->addCategory($categoryId);
        }
        $response = array("message" => "Product categories updated successfully.");
        $this->sendResponse($response);
    }

    private function handleProductImages($productId, $images) {
        if (!empty($images)) {
            // Delete existing images for the product
            $this->deleteProductImages($productId);

            // Insert new images
            foreach ($images as $image) {
                $this->productImages->product_id = $productId;
                $this->productImages->image_path = $image['path'];
                $this->productImages->is_primary = $image['is_primary'];
                $this->productImages->create();
            }
        }
    }

    private function deleteProductImages($productId) {
        $this->productImages->product_id = $productId;
        $stmt = $this->productImages->read();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($images as $image) {
            $this->productImages->id = $image['id'];
            $this->productImages->delete();
        }
    }
    
    private function validateProductData($data) {
        $validatedData = array();
    
        $validatedData['name'] = isset($data['name']) && is_string($data['name']) ? trim($data['name']) : null;
    
        $validatedData['brand'] = isset($data['brand']) && is_string($data['brand']) ? trim($data['brand']) : null;
    
        $validatedData['description'] = isset($data['description']) && is_string($data['description']) ? trim($data['description']) : null;
    
        $validatedData['price'] = isset($data['price']) && is_numeric($data['price']) ? (float) $data['price'] : null;
    
        $validatedData['discount_id'] = isset($data['discount_id']) && !empty($data['discount_id']) ? $data['discount_id'] : 0;
    
        $validatedData['size_id'] = isset($data['size_id']) && !empty($data['size_id']) ? $data['size_id'] : 0;
    
        $validatedData['quantity'] = isset($data['quantity']) && is_numeric($data['quantity']) ? (int) $data['quantity'] : 0;
    
        if (is_null($validatedData['name']) || is_null($validatedData['brand']) || is_null($validatedData['description']) || is_null($validatedData['price'])) {
            return null;
        }
    
        return $validatedData;
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
