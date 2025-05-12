<?php
include_once __DIR__ . '/../../../../controller/OrderController.php';
header('Content-Type: application/json');


$orderController = new OrderController();
$status = $_POST['status'] ?? '';


$beginDateInput = $_POST['begin_date'] ?? '';
$endDateInput = $_POST['end_date'] ?? '';

$beginDate = $beginDateInput ? date('Y-m-d H:i:s', strtotime($beginDateInput)) : null;
$endDate = $endDateInput ? date('Y-m-d H:i:s', strtotime($endDateInput)) : null;


$district = $_POST['district'] ?? '';
$province = $_POST['province'] ?? '';
// Giả sử có phương thức `filterOrders` trong controller
$orders = $orderController->filterOrders($status, $beginDate,$endDate, $district,$province);

if ($orders) {
    $ordersHtml = '';
    foreach ($orders as $data) {
        $ordersHtml .= '<tr>
            <td><input type="radio" name="selected_order_id" value="' . htmlspecialchars($data['order']->getId()) . '" form="actionForm"></td>
            <td>' . htmlspecialchars($data['order']->getId()) . '</td>
            <td>' . htmlspecialchars($data['order']->getCustomerId()) . '</td>
            <td>' . htmlspecialchars($data['customer_name']) . '</td>
            <td>' . htmlspecialchars($data['customer_phone']) . '</td>
            <td>' . htmlspecialchars($data['customer_address']) . '</td>
            <td>' . htmlspecialchars($data['status_name']) . '</td>
            <td>' . htmlspecialchars($data['order']->getNote()) . '</td>
            <td>' . htmlspecialchars($data['total_price']) . '</td>
            <td>' . htmlspecialchars($data['order']->getCreatedAt()) . '</td>
            <td class="table_col-action">
                <form method="POST">
                    <input type="hidden" name="order_id" value="' . htmlspecialchars($data['order']->getId()) . '">
                    <button type="submit" name="view_order">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </form>
            </td>
        </tr>';
    }
    echo json_encode(['success' => true, 'orders_html' => $ordersHtml]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không có đơn hàng phù hợp']);
}
?>
