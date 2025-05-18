<?php
include_once __DIR__ . '/../../controller/SupplierController.php';

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$filters = [
    'name' => $_GET['name'] ?? null,
];

$supplierController = new SupplierController($connection);
$supplierData = $supplierController->getListSuppliers($filters, $limit, $page);
$suppliers = $supplierData['suppliers'];
$totalPages = $supplierData['totalPages'];
?>

<div class="supplier-action">
    <form id="filter-form" class="filter_suppliers" method="GET">
        <div class="form-group-supplier">
            <label for="name">Tên nhà cung cấp</label>
            <input type="text" name="name" class="input_text" placeholder="Nhập tên nhà cung cấp">
        </div>
        <div class="form-group-supplier">
            <button type="submit" class="filter-button">Tìm nhà cung cấp</button>
        </div>
    </form>
</div>

<?php if (!empty($suppliers)): ?>
    <table border="1" id="supplier-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên nhà cung cấp</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody id="supplier-table-body">
            <?php if (!empty($suppliers)): ?>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= htmlspecialchars($supplier->getId()) ?></td>
                        <td><?= htmlspecialchars($supplier->getName()) ?></td>
                        <td><?= htmlspecialchars($supplier->getAddress()) ?></td>
                        <td><?= htmlspecialchars($supplier->getPhone()) ?></td>
                        <td><?= htmlspecialchars($supplier->getEmail()) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Không có nhà cung cấp nào.</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <div id="pagination" class="pagination-container">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&name=<?= urlencode($filters['name'] ?? '') ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>


<?php else: ?>
    <p>Không có nhà cung cấp nào.</p>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/ajax-supplier.js"></script>