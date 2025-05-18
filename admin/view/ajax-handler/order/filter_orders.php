<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
header('Content-Type: application/json');

// 1) Trang hiện tại và kích thước
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 5;

// 2) Gom filter từ query string
$filters = [
    'status' => isset($_GET['status']) ? (int)$_GET['status'] : null,
    'begin_date' => isset($_GET['begin_date']) ? $_GET['begin_date'] : '',
    'end_date' => isset($_GET['end_date']) ? $_GET['end_date'] : '',
    'district' => isset($_GET['district']) ? $_GET['district'] : '',
    'province' => isset($_GET['province']) ? $_GET['province'] : '',
];



$orderController = new OrderController();
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

// 5) Render pagination với Prev/Next
ob_start();
// Nếu không phải trang đầu, hiển thị link “Trang trước”
if ($page > 1) {
    $prevPage = $page - 1;
    $qsPrev = $_GET;
    $qsPrev['page'] = $prevPage;
    $linkPrev = htmlspecialchars(http_build_query($qsPrev));
    echo "<a href=\"?{$linkPrev}\" class=\"page-link prev\" data-page=\"{$prevPage}\">« Trang trước</a>";
}

// Hiển thị các số trang (bạn có thể giới hạn hiển thị nếu nhiều trang)
for ($i = 1; $i <= $totalPages; $i++) {
    $active = $i === $page ? 'active-page' : '';
    $qs = $_GET;
    $qs['page'] = $i;
    $link = htmlspecialchars(http_build_query($qs));
    echo "<a href=\"?{$link}\" class=\"page-link {$active}\" data-page=\"{$i}\">{$i}</a>";
}
// Nếu không phải trang cuối, hiển thị link “Trang sau”
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
