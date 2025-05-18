<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;

    if (!$orderId) {
        echo json_encode(['success' => false, 'message' => 'Thiếu order_id']);
        exit;
    }

    $controller = new OrderController($connection);
    $orderDetails = $controller->getProductQuantity($orderId);

    if ($orderDetails) {
        echo json_encode([
            'success' => true,
            'order' => $orderDetails
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy chi tiết đơn hàng']);
    }
    exit;
}
