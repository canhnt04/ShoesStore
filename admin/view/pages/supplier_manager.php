<?php
include_once __DIR__ . '/../../controller/SupplierController.php';
include_once __DIR__ . '/../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
$limit = 5;
try {

    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $filters = [
        'name' => isset($_GET['name']) ? $_GET['name'] : ''
    ];

    $supplierController = new SupplierController($connection);
    $supplierData = $supplierController->getListSuppliers($filters, $limit, $page);
    $suppliers = $supplierData['suppliers'];
    $totalPages = $supplierData['totalPages'];
} catch (Exception $e) {
    echo '<p>Lỗi: Không thể tải danh sách nhà cung cấp.</p>';
    exit;
}
?>

<div class="supplier-action">
    <form id="supplier-form" class="filter_suppliers" method="GET">
        <div class="form-group-supplier">
            <label for="search-supplier-input">Tên nhà cung cấp</label>
            <input type="text" name="name" placeholder="Nhập nhà cung cấp">
        </div>
        <div class="form-group-supplier">
            <button type="button" id="btn-search" class="filter-button">Tìm nhà cung cấp</button>
        </div>
    </form>
</div>

<table border="1" id="supplier-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên nhà cung cấp</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody id="supplier-table-body">


    </tbody>
</table>

<div id="pagination" class="pagination-container">

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/ajax-supplier.js"></script>