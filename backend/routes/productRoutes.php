<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
include_once '../controllers/ProductController.php';
include_once '../models/Product.php';

$productController = new ProductController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            if (isset($_GET['categories'])) {
                $productController->getCategories($id);
            } else {
                $productController->getById($id);
            }
        } else {
            $productController->index();
        }
        break;    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $productController->create($data);
        if (isset($data['product_id']) && isset($data['category_id'])) {
            $productController->addCategory($data['category_id'], $data['product_id']);
        } else {
            echo json_encode(array("message" => "Product ID and Category ID are required."));
        }
        break;        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $data['id'] = $id;
            $productController->update($data);
            if (isset($data['category_ids']) && is_array($data['category_ids'])) {
                $productController->updateCategories($data['category_ids']);
            }
        } else {
            echo json_encode(array("message" => "Product ID not provided or invalid."));
        }
        break;
    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $productController->delete($id);
        } else {
            echo json_encode(array("message" => "Product ID not provided or invalid."));
        }
        break;
    default:
        echo json_encode(array("message" => "Invalid request method."));
        break;
}
?>
