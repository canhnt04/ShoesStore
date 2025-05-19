<?php
include_once __DIR__ . '/../../controller/CustomerController.php';
header('Content-Type: application/json');
include_once __DIR__ . '/../../../config/init.php';


$database = new Database();
$connection = $database->getConnection();
$customerController = new CustomerController($connection);

$beginDate = isset($_GET['begin_date']) ? $_GET['begin_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

date_default_timezone_set('Asia/Ho_Chi_Minh');
$current = new DateTime();
$currentStandard = $current->format('Y-m-d H:i:s');

if (empty($endDate)) {
    $endDate = $currentStandard;
}
if (!empty($beginDate) && strtotime($beginDate) > strtotime($currentStandard)) {
    echo json_encode([
        'success' => false,
        'message' => 'Ngày bắt đầu không được lớn hơn ngày hiện tại',
    ]);
    exit;
}
if (!empty($endDate) && strtotime($endDate) > strtotime($currentStandard)) {
    echo json_encode([
        'success' => false,
        'message' => 'Ngày kết thúc không được lớn hơn ngày hiện tại',
    ]);
    exit;
}
if (strtotime($beginDate) > strtotime($endDate)) {
    echo json_encode([
        'success' => false,
        'message' => 'Ngày bắt đầu không được lớn hơn ngày kết thúc',
    ]);
    exit;
}

$sortOrder = $_GET['sort_order'] === 'asc' ? 'ASC' : 'DESC';



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
                <a href="ajax-handler/order/list_order_customer.php?user_id=' . $row['user_id'] .
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
