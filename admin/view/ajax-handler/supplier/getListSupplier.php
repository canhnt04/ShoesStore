<?php
include_once __DIR__ . '/../../../controller/SupplierController.php';
header('Content-Type: application/json');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 5;
$filters = [
    'name' => isset($_GET['name']) ? trim($_GET['name']) : '',
];

$supplierController = new SupplierController($filters);
$data = $supplierController->getListSuppliers($filters, $perPage, $page);

$suppliers = $data['suppliers'];
$totalPages = $data['totalPages'];

ob_start();
if (!empty($suppliers)) {
    foreach ($suppliers as $supplier) {
        $id      = $supplier->getId();
        $name    = $supplier->getName();
        $address = $supplier->getAddress();
        $phone   = $supplier->getPhone();
        $email   = $supplier->getEmail();
?>
        <tr>
            <td><?= htmlspecialchars($id) ?></td>
            <td><?= htmlspecialchars($name) ?></td>
            <td><?= htmlspecialchars($address) ?></td>
            <td><?= htmlspecialchars($phone) ?></td>
            <td><?= htmlspecialchars($email) ?></td>
        </tr>
<?php
    }
} else {
    echo '<tr><td colspan="6">Không có nhà cung cấp nào.</td></tr>';
}

$tbody = ob_get_clean();


// Render pagination
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

// Return JSON
echo json_encode([
    'success' => true,
    'tbody' => $tbody,
    'pagination' => $pagination,
]);
exit;
