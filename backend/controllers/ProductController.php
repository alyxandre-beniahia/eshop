<?php
include_once '../config/db.php';
include_once '../models/Product.php';
include_once '../models/Stock.php';
include_once '../models/ProductImages.php';
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
            foreach ($products as &$product) {
                $this->productImages->product_id = $product['id'];
                $stmt = $this->productImages->read();
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $product['images'] = array_column($images, 'image_path');
    
                // Get stock information for the product
                $this->product->id = $product['id'];
                $stockInfo = $this->product->getStock();
                $product['stock'] = $stockInfo;
            }
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
    
        if ($this->product->create()) {
            $productId = $this->db->lastInsertId();
    
            // Extract size quantities from the request data
            $sizeQuantities = isset($data['sizeQuantities']) ? $data['sizeQuantities'] : [];
    
            // Insert stock entries for each size
            foreach ($sizeQuantities as $sizeId => $quantity) {
                $this->stock->insertProductStock($productId, $sizeId, $quantity);
            }
    
            // Handle product images
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
    
        if (is_null($this->product->id) || is_null($this->product->name) || is_null($this->product->brand) || is_null($this->product->description) || is_null($this->product->price)) {
            $response = array("message" => "Required fields cannot be empty.");
            $this->sendResponse($response, 400);
            return;
        }
    
        if ($this->product->update()) {
            // Update stock quantities
            if (isset($data['stock']) && is_array($data['stock'])) {
                foreach ($data['stock'] as $stockItem) {
                    $sizeId = $stockItem['size_id'];
                    $quantity = $stockItem['quantity'];
                    $productId = $this->product->id;
                    
                    // Log the values
                    echo "Product ID: $productId, Size ID: $sizeId, Quantity: $quantity\n";
                    
                    $this->updateProductStock($productId, $sizeId, $quantity);
                }
                
            }
    
            // If the size_id has changed, update the stock accordingly
            if (isset($data['size_id']) && $data['size_id']) {
                $newSizeId = $data['size_id'];
                $existingStock = $this->stock->readBySize($newSizeId);
    
                if ($existingStock) {
                    // Update existing stock quantity for the new size
                    $this->stock->updateProductStock($this->product->id, $newSizeId, $existingStock['quantity']);
                } else {
                    // Insert new stock entry for the new size with a quantity of 0
                    $this->stock->insertProductStock($this->product->id, $newSizeId, 0);
                }
            }
    
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
            // Insert new images
            foreach ($images as $image) {
                $imagePath = $this->saveBase64Image($image['path']);
                if ($imagePath) {
                    $this->productImages->product_id = $productId;
                    $this->productImages->image_path = $imagePath;
                    $this->productImages->is_primary = isset($image['is_primary']) ? $image['is_primary'] : 0;
                    $this->productImages->create();
                }
            }
        }
    }
    
    private function saveBase64Image($base64Image) {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $uploadsDir = realpath(__DIR__ . '/../public/uploads/'); // Get the absolute path to the uploads directory
        $imageName = uniqid() . '.png';
        $imagePath = $uploadsDir .'/'. $imageName;
    
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true); // Create the uploads directory if it doesn't exist
        }
    
        $success = file_put_contents($imagePath, $imageData);
    
        if ($success) {
            return 'uploads/' . $imageName; // Return a relative path
        }
    
        return false;
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
    
        // Validate fields for both create and update
        $validatedData['name'] = isset($data['name']) && is_string($data['name']) ? trim($data['name']) : null;
        $validatedData['brand'] = isset($data['brand']) && is_string($data['brand']) ? trim($data['brand']) : null;
        $validatedData['description'] = isset($data['description']) && is_string($data['description']) ? trim($data['description']) : null;
        $validatedData['price'] = isset($data['price']) && is_numeric($data['price']) && $data['price'] > 0 ? (float) $data['price'] : null;
        $validatedData['discount_id'] = isset($data['discount_id']) && !empty($data['discount_id']) ? $data['discount_id'] : 0;
        $validatedData['quantity'] = isset($data['quantity']) && is_numeric($data['quantity']) && $data['quantity'] >= 0 ? (int) $data['quantity'] : 0;
    
        // Validate id only for update
        if (isset($data['id']) && is_numeric($data['id'])) {
            $validatedData['id'] = (int) $data['id'];
        }
    
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
