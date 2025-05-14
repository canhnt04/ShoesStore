<?php
include_once __DIR__ . '/../../../../controller/OrderController.php';

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $orderModel = new OrderController(); // Khởi tạo đối tượng OrderController

    $orders = $orderModel->getOrdersByOrderId($orderId); // Trả về mảng thông tin đơn hàng

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
        <?php $stt = 1;
        foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_detail_id']) ?></td>
                <td><?= htmlspecialchars($order['payment_method_name']) ?></td>
                <td><?= htmlspecialchars($order['status_name']) ?></td>
                <td><?= htmlspecialchars($order['product_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($order['quantity'] ?? '-') ?></td>
                <td><?= isset($order['total_price']) ? number_format($order['total_price'], 0, ',', '.') . 'đ' : '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>