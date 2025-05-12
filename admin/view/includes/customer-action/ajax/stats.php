<?php
include_once __DIR__ . '/../../../../controller/CustomerController.php';
header('Content-Type: application/json');

$customerController = new CustomerController();

$beginDateInput = $_GET['begin_date'] ?? '';
$endDateInput = $_GET['end_date'] ?? '';
$sortOrder = $_GET['sort_order'] === 'asc' ? 'ASC' : 'DESC';

$beginDate = $beginDateInput ? date('Y-m-d 00:00:00', strtotime($beginDateInput)) : null;
$endDate = $endDateInput ? date('Y-m-d 23:59:59', strtotime($endDateInput)) : null;

$results = $customerController->getTopCustomers($beginDate, $endDate, $sortOrder);

if ($results) {
    $html = '<table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên khách hàng</th>
                <th>Tổng mua hàng</th>
                <th>Danh sách đơn hàng</th>
            </tr>
        </thead>
        <tbody>';

    $stt = 1;
    foreach ($results as $row) {
        $html .= '<tr>
            <td>' . $stt++ . '</td>
            <td>' . htmlspecialchars($row['customer_name']) . '</td>
            <td>' . number_format($row['total_spent'], 0, ',', '.') . 'đ</td>
            <td>
                <a href="includes/customer-action/ajax/list_order_customer.php?customer_id=' . $row['customer_id'] .
            '&begin_date=' . urlencode($beginDate) .
            '&end_date=' . urlencode($endDate) .
            '&sort_order=' . urlencode($sortOrder) . '" class="view-orders">Xem chi tiết</a>
            </td>
        </tr>';
    }

    $html .= '</tbody></table>';

    echo json_encode([
        'success' => true,
        'html' => $html,
        'chartData' => [
            'labels' => array_column($results, 'customer_name'),
            'data' => array_column($results, 'total_spent'),
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không có dữ liệu thống kê.']);
}
