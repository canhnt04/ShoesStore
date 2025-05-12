<?php
include_once __DIR__ . '/../../../../controller/OrderController.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $orderId = $_POST["selected_order_id"] ?? null;
    $action = $_POST["action"] ?? null;

    $statusMap = [
        'approve_order' => 3,
        'cancel_order' => 4,
        'confirm_delivery' => 5
    ];

    if ($orderId && isset($statusMap[$action])) {
        $controller = new OrderController();
        $success = $controller->updateOrderStatus($orderId, $statusMap[$action]);

        echo json_encode([
            "success" => $success,
            "new_status" => $statusMap[$action]
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}
