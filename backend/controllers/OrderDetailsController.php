<?php
include_once '../models/OrderDetails.php';

class OrderDetailsController {
    private $orderDetailsModel;

    public function __construct($db) {
        $this->orderDetailsModel = new OrderDetails($db);
    }

    public function index() {
        $orderDetails = $this->orderDetailsModel->read();
        echo json_encode($orderDetails);
    }

    public function create($data) {
        if (!isset($data['customer_id']) || !isset($data['status']) || !isset($data['total'])) {
            echo json_encode(array("message" => "Missing required data."));
            return;
        }

        $this->orderDetailsModel->customer_id = $data['customer_id'];
        $this->orderDetailsModel->status = $data['status'];
        $this->orderDetailsModel->total = $data['total'];
        $this->orderDetailsModel->created_at = date('Y-m-d H:i:s');
        $this->orderDetailsModel->modified_at = date('Y-m-d H:i:s');

        if($this->orderDetailsModel->create()) {
            echo json_encode(array("message" => "Order details created successfully."));
        } else {
            echo json_encode(array("message" => "Unable to create order details."));
        }
    }

    public function update($data) {
        if (!isset($data['id']) || !isset($data['customer_id']) || !isset($data['status']) || !isset($data['total']) || !isset($data['created_at'])) {
            echo json_encode(array("message" => "Missing required data."));
            return;
        }

        $this->orderDetailsModel->id = $data['id'];
        $this->orderDetailsModel->customer_id = $data['customer_id'];
        $this->orderDetailsModel->status = $data['status'];
        $this->orderDetailsModel->total = $data['total'];
        $this->orderDetailsModel->created_at = $data['created_at'];
        $this->orderDetailsModel->modified_at = date('Y-m-d H:i:s');

        if($this->orderDetailsModel->update()) {
            echo json_encode(array("message" => "Order details updated successfully."));
        } else {
            echo json_encode(array("message" => "Unable to update order details."));
        }
    }

    public function delete($id) {
        $this->orderDetailsModel->id = $id;

        if($this->orderDetailsModel->delete()) {
            echo json_encode(array("message" => "Order details deleted successfully."));
        } else {
            echo json_encode(array("message" => "Unable to delete order details."));
        }
    }

    public function getById($id) {
        $this->orderDetailsModel->id = $id;
        $orderDetails = $this->orderDetailsModel->getById($id);
        if ($orderDetails) {
            echo json_encode($orderDetails);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Order details not found."));
        }
    }
}
?>