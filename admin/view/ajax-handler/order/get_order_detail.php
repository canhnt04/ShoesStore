<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
$orders = [];

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $orderModel = new OrderController($connection);
    $orders = $orderModel->getDetailsByOrderId($orderId) ?? [];
}
?>
<table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Phương thức thanh toán</th>
            <th>Trạng thái</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Tổng tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_detail_id']) ?></td>
                    <td><?= htmlspecialchars($order['payment_method']) ?></td>
                    <td><?= htmlspecialchars($order['status_name']) ?></td>
                    <td><?= htmlspecialchars($order['product_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($order['quantity'] ?? '-') ?></td>
                    <td><?= isset($order['total_price']) ? number_format($order['total_price'], 0, ',', '.') . ' VND' : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">Không có dữ liệu đơn hàng</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>