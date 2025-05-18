<?php
include_once __DIR__ . '/../../../controller/OrderController.php';

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$filters = [
    'status' => $_GET['status'] ?? null,
    'begin_date' => $_GET['begin_date'] ?? null,
    'end_date' => $_GET['end_date'] ?? null,
    'district' => $_GET['district'] ?? null,
    'province' => $_GET['province'] ?? null,
];

$orderController = new OrderController($connection);
$orderData = $orderController->listOrders($filters, $limit, $page);
$orders = $orderData['orders'];
$totalOrders = $orderData['totalCount']; // Đây nên là số đơn hàng sau khi filter
$totalPages = ceil($totalOrders / $limit);
?>


<div class="order-action">
    <div class="group-button_order-action">
        <form id="actionForm">
            <button type="button" class="button_order-action blue" data-action="approve_order">
                <i class="fa-solid fa-user-pen"></i>
                <span>Xác nhận đơn hàng</span>
            </button>
            <button type="button" class="button_order-action yellow" data-action="cancel_order">
                <i class="fa-solid fa-user-pen"></i>
                <span>Xác nhận hủy đơn</span>
            </button>
            <button type="button" class="button_order-action red" data-action="confirm_delivery">
                <i class="fa-solid fa-check"></i>
                <span>Xác nhận đã giao</span>
            </button>
        </form>
    </div>

    <form id="filter-form" class="filter_orders" method="GET">
        <div class="form-group-order">
            <label for="status">Trạng thái</label>
            <select name="status" class="">
                <option value="">Tất cả trạng thái</option>
                <option value="1">Order Placed</option>
                <option value="2">Order Paid</option>
                <option value="3">Order Shipped Out</option>
                <option value="4">Order Canceled</option>
                <option value="5">Order Received</option>
            </select>
        </div>
        <div class="form-group-order">
            <label for="begin_date">Từ ngày</label>
            <input type="datetime-local" name="begin_date" class="input_date">
        </div>
        <div class="form-group-order">
            <label for="end_date">Đến ngày</label>
            <input type="datetime-local" name="end_date" class="input_date">
        </div>
        <div class="form-group-order">
            <label for="district">Quận/Huyện</label>
            <input type="text" name="district" placeholder="Nhập quận/huyện">
        </div>
        <div class="form-group-order">
            <label for="province">Tỉnh/Thành phố</label>
            <input type="text" name="province" placeholder="Nhập tỉnh/thành phố">
        </div>
        <div class="form-group-order">
            <button type="submit" class="filter-button">Lọc đơn hàng</button>
        </div>

    </form>
</div>
<?php if (!empty($orders)): ?>
    <table border="1" id="order-table">
        <thead>
            <tr>
                <th>Chọn</th>
                <th>ID</th>
                <th>Mã khách hàng</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
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
    <div class="modal" id="approveOrderModal" style="display:none;">
        <div class="modal-content custom-modal-content">
            <h3>Xác nhận duyệt đơn hàng</h3>
            <div id="modalOrderInfo" style="margin: 10px 0;"></div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="$('#approveOrderModal').fadeOut()">Hủy</button>
                <button class="btn btn-success" id="confirmApproveBtn">Xác nhận duyệt</button>
            </div>
        </div>
    </div>




<?php else: ?>
    <p>Không có hóa đơn nào.</p>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/ajax-order.js"></script>