<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
header('Content-Type: application/json');

// 1) Trang hiện tại và kích thước
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 5;

$beginDate = isset($_GET['begin_date']) ? $_GET['begin_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

date_default_timezone_set('Asia/Ho_Chi_Minh');
$current = new DateTime();
$currentStandard = $current->format('Y-m-d H:i:s');


if(empty($endDate)){
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







$filters = [
    'status' => isset($_GET['status']) ? (int)$_GET['status'] : null,
    'begin_date' => $beginDate,
    'end_date' => $endDate,
    'district' => isset($_GET['district']) ? $_GET['district'] : '',
    'province' => isset($_GET['province']) ? $_GET['province'] : '',
];



$orderController = new OrderController($connection);
$data  = $orderController->listOrders($filters, $perPage, $page);

$orders     = $data['orders'];
$totalPages = $data['totalPages'];

ob_start();
foreach ($orders as $row) {
    $o = $row['order'];
?>
    <tr>
        <td><input type="radio" name="selected_order_id" value="<?= htmlspecialchars($o->getId()) ?>" form="actionForm"></td>
        <td><?= htmlspecialchars($o->getId()) ?></td>
        <td><?= htmlspecialchars($o->getUserId()) ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['customer_phone']) ?></td>
        <td><?= htmlspecialchars($row['customer_address']) ?></td>
        <td><?= htmlspecialchars($row['status_name']) ?></td>
        <td><?= htmlspecialchars($o->getNote()) ?></td>
        <td>
            <?= isset($row['total_price'])
                ? number_format($row['total_price'], 0, ',', '.') . ' VND'
                : '-' ?>
        </td>
        <td><?= htmlspecialchars($o->getCreatedAt()) ?></td>
        <td class="table_col-action">
            <button type="button" class="btn-view" data-id="<?= htmlspecialchars($o->getId()) ?>">
                <i class="fa-solid fa-eye"></i>
            </button>
        </td>
    </tr>
<?php
}
$tbody = ob_get_clean();

ob_start();
if ($page > 1) {
    $prevPage = $page - 1;
    $qsPrev = $_GET;
    $qsPrev['page'] = $prevPage;
    $linkPrev = htmlspecialchars(http_build_query($qsPrev));
    echo "<a href=\"?{$linkPrev}\" class=\"page-link prev\" data-page=\"{$prevPage}\">« Trang trước</a>";
}

for ($i = 1; $i <= $totalPages; $i++) {
    $active = $i === $page ? 'active-page' : '';
    $qs = $_GET;
    $qs['page'] = $i;
    $link = htmlspecialchars(http_build_query($qs));
    echo "<a href=\"?{$link}\" class=\"page-link {$active}\" data-page=\"{$i}\">{$i}</a>";
}
if ($page < $totalPages) {
    $nextPage = $page + 1;
    $qsNext = $_GET;
    $qsNext['page'] = $nextPage;
    $linkNext = htmlspecialchars(http_build_query($qsNext));
    echo "<a href=\"?{$linkNext}\" class=\"page-link next\" data-page=\"{$nextPage}\">Trang sau »</a>";
}
$pagination = ob_get_clean();


echo json_encode([
    'success' => true,
    'tbody'      => $tbody,
    'pagination' => $pagination,
]);
