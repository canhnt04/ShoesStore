<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $orderId = isset($_POST["selected_order_id"]) ? intval($_POST["selected_order_id"]) : null;
    $action  = $_POST["action"] ?? null;

    // Map action thành status code
    $statusMap = [
        'approve_order'     => 3,  // Đã duyệt
        'cancel_order'      => 4,  // Đã hủy
        'confirm_delivery'  => 5   // Đã giao
    ];

    if ($orderId && isset($statusMap[$action])) {
        $controller = new OrderController($connection);

        // 1. Update trạng thái đơn hàng
        $newStatus    = $statusMap[$action];
        $statusResult = $controller->updateOrderStatus($orderId, $newStatus);


        // 2. Nếu là duyệt đơn, giảm tồn kho
        if ($statusResult && $action === 'approve_order') {
            $stockResult = $controller->updateAmountProduct($orderId);

            if (! $stockResult) {
                // Nếu cập nhật tồn kho lỗi, bạn có thể rollback trạng thái hoặc báo lỗi
                echo json_encode([
                    "success"    => false,
                    "message"    => "Cập nhật tồn kho thất bại",
                    "new_status" => $newStatus
                ]);
                exit;
            }
        }
        // 3. Nếu là hủy đơn, trả kho   
        if ($statusResult && $action === 'cancel_order') {
            $stockResult = $controller->restoreAmountProduct($orderId);

            if (! $stockResult) {
                echo json_encode([
                    "success"    => false,
                    "message"    => "Cập nhật tồn kho thất bại",
                    "new_status" => $newStatus
                ]);
                exit;
            }
        }

        // Trả về kết quả chung
        echo json_encode([
            "success"    => (bool)$statusResult,
            "new_status" => $newStatus
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Thiếu order_id hoặc action không hợp lệ"
        ]);
    }
    exit;
}
