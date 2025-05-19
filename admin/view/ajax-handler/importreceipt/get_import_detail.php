<?php
include_once __DIR__ . '/../../../controller/ImportController.php';
include_once __DIR__ . '/../../../controller/SupplierController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
$imports = [];

if (isset($_GET['id'])) {
    $importId = $_GET['id'];
    $importController = new ImportController($connection);
    $supplierController = new SupplierController($connection);
    $imports = $importController->getDetailsByImportId($importId) ?? [];
}
?>
<table bimport="1" cellpadding="8" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>ID Chi Tiết</th>
            <th>ID Nhân Viên</th>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Màu sắc</th>
            <th>Chất liệu</th>
            <th>Số lượng</th>
            <th>Giá Nhập</th>
            <th>Nhà cung cấp</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($imports)): ?>
            <?php foreach ($imports as $import): ?>
                <tr>
                    <td><?= htmlspecialchars($import['importdetail_id']) ?></td>
                    <td><?= htmlspecialchars($import['import']->getUserId() ?? '-') ?></td>

                    <td><?= htmlspecialchars($import['product_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($import['product_size'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($import['product_color'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($import['product_material'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($import['quantity'] ?? '-') ?></td>
                    <td><?= isset($import['price']) ? number_format($import['price'], 0, ',', '.') . ' VND' : '-' ?></td>
                    <td><?= htmlspecialchars($import['supplier_name'] ?? '-') ?></td>

                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">Không có dữ liệu phiếu nhập</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>