<?php
include_once '../controllers/ProductController.php';
include_once '../models/Product.php';

$productController = new ProductController();
echo "ProductController instantiated";
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        // Check if an ID is provided
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $productController->getById($id);
        } else {
            $productController->index();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $productController->create($data);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $data['id'] = $id; // C'est un peu caca mais j'ai fait comme ça
            $productController->update($data);
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
