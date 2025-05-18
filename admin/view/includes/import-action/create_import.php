<?php
include_once __DIR__ . '/../../../controller/ImportController.php';

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$filters = [
    'begin_date' => $_GET['begin_date'] ?? null,
    'end_date' => $_GET['end_date'] ?? null,
];

$importController = new ImportController();
$importData = $importController->getListImports($filters, $limit, $page);
$imports = $importData['imports'];
$totalPages = $importData['totalPages'];
?>

<div style="margin-bottom: 20px;">
    <select id="supplier-select" name="supplier_id" required>
        <option value="">-- Chọn nhà cung cấp --</option>
    </select>
</div>
<div id="product-variant-container">
    <div class="table-wrapper-product">
        <h2>Bảng sản phẩm</h2>
        <table id="product-table-import">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="body-product"></tbody>
        </table>
    </div>

    <div class="table-wrapper-detail">
        <h2>Bảng chi tiết sản phẩm</h2>
        <table id="product-variant-table">
            <thead>
                <tr>
                    <th>ID chi tiết</th>
                    <th>Size</th>
                    <th>Màu sắc</th>
                    <th>Chất liệu</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="variant-table-body"></tbody>
        </table>
    </div>
</div>

</div>

<form id="import-form">
    <!-- Nhập số lượng và giá -->
    <div id="import-info" class="form-section">
        <label for="quantity">Số lượng:</label>
        <input type="number" id="quantity" min="1" value="1" required>

        <label for="price">Giá nhập:</label>
        <input type="number" id="price" min="0" step="1000" value="0" required>

        <button type="button" id="add-detail-btn">Thêm chi tiết phiếu nhập</button>
    </div>

    <!-- Bảng chi tiết phiếu nhập -->
    <div class="form-section">
        <table id="details-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID chi tiết sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody id="details-table-body"></tbody>
        </table>
    </div>

    <button type="submit" id="submit-btn">Lưu phiếu nhập</button>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/ajax-import-create.js">

</script>