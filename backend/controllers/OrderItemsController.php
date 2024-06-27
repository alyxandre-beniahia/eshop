<?php
class OrderItemsController {
    private $conn;
    private $orderItems;

    public function __construct($db) {
        $this->conn = $db;
        $this->orderItems = new OrderItems($db);
    }

    public function index() {
        $stmt = $this->orderItems->read();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($items);
    }

    public function create() {
        $this->orderItems->order_id = $_POST['order_id'];
        $this->orderItems->product_id = $_POST['product_id'];
        $this->orderItems->quantity = $_POST['quantity'];
        $this->orderItems->created_at = date('Y-m-d H:i:s');
        $this->orderItems->modified_at = date('Y-m-d H:i:s');

        if($this->orderItems->create()) {
            echo "Order item created successfully.";
        } else {
            echo "Unable to create order item.";
        }
    }

    public function read() {
        $stmt = $this->orderItems->read();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($items);
    }

    public function update() {
        $this->orderItems->id = $_POST['id'];
        $this->orderItems->order_id = $_POST['order_id'];
        $this->orderItems->product_id = $_POST['product_id'];
        $this->orderItems->quantity = $_POST['quantity'];
        $this->orderItems->created_at = $_POST['created_at'];
        $this->orderItems->modified_at = date('Y-m-d H:i:s');

        if($this->orderItems->update()) {
            echo "Order item updated successfully.";
        } else {
            echo "Unable to update order item.";
        }
    }

    public function delete() {
        $this->orderItems->id = $_POST['id'];

        if($this->orderItems->delete()) {
            echo "Order item deleted successfully.";
        } else {
            echo "Unable to delete order item.";
        }
    }
}
?>