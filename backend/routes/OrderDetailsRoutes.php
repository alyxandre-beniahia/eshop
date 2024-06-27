<?php
include_once '../controllers/OrderDetailsController.php';
include_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$orderDetailsController = new OrderDetailsController($db);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($requestMethod) {
    case 'GET':
        if ($path === '/order_details') {
            $orderDetailsController->index();
        } else {
            $pathParts = explode('/', $path);
            if (count($pathParts) === 3 && $pathParts[1] === 'order_details') {
                $id = intval($pathParts[2]);
                if ($id > 0) {
                    $orderDetailsController->getById($id);
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Invalid order details ID."));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Route not found."));
            }
        }
        break;

    case 'POST':
        if ($path === '/order_details') {
            $data = json_decode(file_get_contents("php://input"), true);
            $orderDetailsController->create($data);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Route not found."));
        }
        break;

    case 'PUT':
        if ($path === '/order_details') {
            $data = json_decode(file_get_contents("php://input"), true);
            $orderDetailsController->update($data);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Route not found."));
        }
        break;

    case 'DELETE':
        if ($path === '/order_details') {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = isset($data['id']) ? intval($data['id']) : null;
            if ($id !== null) {
                $orderDetailsController->delete($id);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Order details ID not provided or invalid."));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Route not found."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>