<?php
include_once __DIR__ . '/../../controller/OrderController.php';

$customerId = $_GET['user_id'] ?? null;
$beginDate = $_GET['begin_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

if (!$customerId || !$beginDate || !$endDate) {
    echo "Thiếu tham số customer_id, begin_date hoặc end_date.";
    exit;
}

$orderController = new OrderController();
$orders = $orderController->getOrdersByCustomerIdAndDateRange($customerId, $beginDate, $endDate);

if (!$orders || count($orders) === 0) {
    echo '<p>Không có đơn hàng nào.</p>';
    exit;
}
?>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Phương thức thanh toán</th>
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
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                <td><?= htmlspecialchars($order['customer_address']) ?></td>
                <td><?= htmlspecialchars($order['order']->getCreatedAt()) ?></td>
                <td><?= htmlspecialchars($order['status_name']) ?></td>
                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                <td><?= htmlspecialchars($order['product_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($order['quantity'] ?? '-') ?></td>
                <td><?= isset($order['total_price']) ? number_format($order['total_price'], 0, ',', '.') . 'đ' : '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>