<?php
include_once '../controllers/UserController.php';

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200); // Send a successful HTTP status code
    exit;
}

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
