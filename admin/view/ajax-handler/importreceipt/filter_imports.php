<?php
include_once __DIR__ . '/../../../controller/ImportController.php';
header('Content-Type: application/json');


$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 5;
$filters = [
    'begin_date' => isset($_GET['begin_date']) ? $_GET['begin_date'] : '',
    'end_date'   => isset($_GET['end_date'])   ? $_GET['end_date']   : '',
];

$importController = new ImportController();
$data = $importController->getListImports($filters, $perPage, $page);
$imports = $data['imports'];
$totalRecords = $data['totalPages'];


ob_start();
if (!empty($imports)) {
  

    foreach ($imports['imports'] as $import) {
        // Dùng getter thay vì truy cập thuộc tính private
        $id = $import->getId();
        $userId = $import->getUserId();
        $supplierId = $import->getSupplierId();
        $totalPrice = $import->getTotalPrice();
        $salePrice = $import->getSalePrice();
        $createdAt = $import->getCreatedAt();
?>
        <tr>
            <td><input type="radio" name="selected_order_id" value="<?= htmlspecialchars($id) ?>" form="actionForm"></td>
            <td><?= htmlspecialchars($userId) ?></td>
            <td><?= htmlspecialchars($supplierId) ?></td>
            <td><?= htmlspecialchars($totalPrice) ?></td>
            <td><?= htmlspecialchars($salePrice) ?></td>
            <td><?= htmlspecialchars($createdAt) ?></td>
            <td class="table_col-action">
                <button type="button" class="btn-view" data-id="<?= htmlspecialchars($id) ?>">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </td>
        </tr>
<?php
    }
} else {
    echo '<tr><td colspan="7">Không có dữ liệu</td></tr>';
}
$tbody = ob_get_clean();

ob_start();
// Nếu không phải trang đầu, hiển thị link “Trang trước”
if ($page > 1) {
    $prevPage = $page - 1;
    $qsPrev = $_GET;
    $qsPrev['page'] = $prevPage;
    $linkPrev = htmlspecialchars(http_build_query($qsPrev));
    echo "<a href=\"?{$linkPrev}\" class=\"page-link prev\" data-page=\"{$prevPage}\">« Trang trước</a>";
}

// Hiển thị các số trang
for ($i = 1; $i <= $totalRecords; $i++) {
    $active = $i === $page ? 'active-page' : '';
    $qs = $_GET;
    $qs['page'] = $i;
    $link = htmlspecialchars(http_build_query($qs));
    echo "<a href=\"?{$link}\" class=\"page-link {$active}\" data-page=\"{$i}\">{$i}</a>";
}

// Nếu không phải trang cuối, hiển thị link “Trang sau”
if ($page < $totalRecords) {
    $nextPage = $page + 1;
    $qsNext = $_GET;
    $qsNext['page'] = $nextPage;
    $linkNext = htmlspecialchars(http_build_query($qsNext));
    echo "<a href=\"?{$linkNext}\" class=\"page-link next\" data-page=\"{$nextPage}\">Trang sau »</a>";
}
$pagination = ob_get_clean();
ob_get_clean();
echo json_encode([
    'success'    => true,
    'tbody'      => $tbody,
    'pagination' => $pagination,
]);
exit;
