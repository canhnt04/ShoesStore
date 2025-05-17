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
$imports= $importData['imports'];
$totalPages = $importData['totalPages'];
?>

<div class="order-action">
    <form id="filter-form" class="filter_orders" method="GET">
        <div class="form-group-order">
            <label for="begin_date">Từ ngày</label>
            <input type="datetime-local" name="begin_date" class="input_date">
        </div>
        <div class="form-group-order">
            <label for="end_date">Đến ngày</label>
            <input type="datetime-local" name="end_date" class="input_date">
        </div>
        <div class="form-group-order">
            <button type="submit" class="filter-button">Tìm phiếu nhập</button>
        </div>

    </form>
</div>
<?php if (!empty($imports)): ?>
    <table border="1" id="order-table">
        <thead>
            <tr>
                <th>Chọn</th>
                <th>ID</th>
                <th>Nhà cung cấp</th>
                <th>Tổng tiền</th>
                <th>Ngày tạo</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody id="order-table-body">


        </tbody>
    </table>

    <div id="pagination" class="pagination-container">

    </div>


    <!-- Modal xem chi tiết -->
    <div id="order-modal" style="display:none;" class="modal">
        <div class="modal-content">
            <button class="btn-close" onclick="$('#order-modal').hide()">Đóng</button>
            <div id="modal-body"></div>
        </div>
    </div>

    <!-- Modal xác nhận duyệt đơn hàng -->




<?php else: ?>
    <p>Không có phiếu nhập nào.</p>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/ajax-import.js"></script>