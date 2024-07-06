<?php
include_once '../controllers/SizeController.php';

$sizeController = new SizeController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $sizeController->index();
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $sizeController->create($data);
        break;
    case 'PUT':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $data = json_decode(file_get_contents("php://input"), true);
            $data['id'] = $id;
            $sizeController->update($data);
        } else {
            echo json_encode(array("message" => "Size ID not provided or invalid."));
        }
        break;
    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if ($id !== null) {
            $sizeController->delete($id);
        } else {
            echo json_encode(array("message" => "Size ID not provided or invalid."));
        }
        break;
    default:
        echo json_encode(array("message" => "Invalid request method."));
        break;
}
?>
