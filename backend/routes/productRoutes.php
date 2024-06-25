<?php
include_once '../controllers/ProductController.php';

$productController = new ProductController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $productController->index();
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $productController->create($data);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $data['id'] = $id; // Assign integer id to data array
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
