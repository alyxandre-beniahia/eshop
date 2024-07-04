<?php
include_once '../controllers/ProductCategoryController.php';

$productCategoryController = new ProductCategoryController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            if (isset($_GET['products'])) {
                $productCategoryController->getProductsByCategory($id);
            } else {
                $productCategoryController->getById($id);
            }
        } else {
            $productCategoryController->index();
        }
        break;    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['product_ids']) && is_array($data['product_ids'])) {
            $productCategoryController->create($data);
            foreach ($data['product_ids'] as $productId) {
                $productCategoryController->addProduct($data['id'], $productId);
            }
        } else {
            $productCategoryController->create($data);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $data['id'] = $id;
            $productCategoryController->update($data, $id);
            if (isset($data['product_ids']) && is_array($data['product_ids'])) {
                $productCategoryController->updateProducts($data['product_ids']);
            }
        } else {
            echo json_encode(array("message" => "Category ID not provided or invalid."));
        }
        break;
    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $productCategoryController->delete($id);
        } else {
            echo json_encode(array("message" => "Category ID not provided or invalid."));
        }
        break;
    default:
        echo json_encode(array("message" => "Invalid request method."));
        break;
}
?>
