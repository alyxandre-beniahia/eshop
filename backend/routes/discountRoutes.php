<?php
include_once '../controllers/DiscountController.php';

$discountController = new DiscountController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $discountController->index();
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $discountController->create($data);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $discountController->update($data);
        break;
    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $discountController->delete($id);
        } else {
            echo json_encode(array("message" => "Discount ID not provided or invalid."));
        }
        break;
    default:
        echo json_encode(array("message" => "Invalid request method."));
        break;
}
?>
