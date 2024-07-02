<?php
include_once '../controllers/UserController.php';

$userController = new UserController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$data = json_decode(file_get_contents("php://input"), true) ?: [];

switch ($requestMethod) {
    case 'GET':
        $userId = $userData['userId'];
        $userRole = $userData['userRole'];
        $userController->index();
        break;
    case 'POST':
        if (strpos($requestUri, '/login') !== false) {
            $data = json_decode(file_get_contents("php://input"), true);
            $userController->authenticate($data);
        } else {
            $userController->create($data);
        }
        break;
    case 'PUT':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null){
            $userController->update($data, $id);
        }
        break;
    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $userController->delete($id);
        } else {
            echo json_encode(array("message" => "User ID not provided or invalid."));
        }
        break;
    default:
        echo json_encode(array("message" => "Invalid request method."));
        break;
}
?>
