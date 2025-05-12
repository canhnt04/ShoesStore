<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
$orderController = new OrderController();
$orders = $orderController->listOrders();
?>

<div class="order-action">
    <div class="group-button_account-action">
        <form id="actionForm">
            <button type="button" class="button_account-action blue" data-action="approve_order">
                <i class="fa-solid fa-user-pen"></i>
                <span>Xác nhận đơn hàng</span>
            </button>
            <button type="button" class="button_account-action yellow" data-action="cancel_order">
                <i class="fa-solid fa-user-pen"></i>
                <span>Xác nhận hủy đơn</span>
            </button>
            <button type="button" class="button_account-action red" data-action="confirm_delivery">
                <i class="fa-solid fa-check"></i>
                <span>Xác nhận đã giao</span>
            </button>
        </form>
        <div class="search-box">
            <input type="text" class="input_search_account-action" placeholder="Tìm kiếm hóa đơn" />
        </div>
    </div>

    <form id="filter-form" class="filter_orders" method="POST">
        <select name="status">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="1">Waiting Confirm</option>
            <option value="2">Order Paid</option>
            <option value="3">Order Shipped Out</option>
            <option value="4">Canceled</option>
            <option value="5">Order Received</option>
        </select>
        <label for="begin_date">Từ ngày</label>
        <input type="datetime-local" name="begin_date">
        <label for="end_date">Đến ngày</label>
        <input type="datetime-local" name="end_date">
        <label for="district">Quận/Huyện</label>
        <input type="text" name="district" placeholder="Nhập quận/huyện">
        <label for="province">Tỉnh/Thành phố</label>
        <input type="text" name="province" placeholder="Nhập tỉnh/thành phố">
        <button type="submit" id="filter-button">Lọc</button>
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
            <?php foreach ($orders as $data): ?>
                <tr>
                    <td><input type="radio" name="selected_order_id" value="<?= $data['order']->getId() ?>" form="actionForm"></td>
                    <td><?= htmlspecialchars($data['order']->getId()) ?></td>
                    <td><?= htmlspecialchars($data['order']->getCustomerId()) ?></td>
                    <td><?= htmlspecialchars($data['customer_name']) ?></td>
                    <td><?= htmlspecialchars($data['customer_phone']) ?></td>
                    <td><?= htmlspecialchars($data['customer_address']) ?></td>
                    <td><?= htmlspecialchars($data['status_name']) ?></td>
                    <td><?= htmlspecialchars($data['order']->getNote()) ?></td>
                    <td><?= htmlspecialchars($data['total_price']) ?></td>
                    <td><?= htmlspecialchars($data['order']->getCreatedAt()) ?></td>
                    <td class="table_col-action">
                        <button type="button" name="view_order" class="btn-view" data-id="<?= $data['order']->getId() ?>">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Modal HTML -->
    <div id="order-modal" style="display:none;" class="modal">
        <div class="modal-content">
            <button onclick="$('#order-modal').hide()">Đóng</button>
            <div id="modal-body"></div>
        </div>
    </div>


<?php else: ?>
    <p>Không có hóa đơn nào.</p>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // thay doi trang thai
    $(document).ready(function() {
        $(".group-button_account-action button").click(function() {
            const action = $(this).data("action");
            const orderId = $("input[name='selected_order_id']:checked").val();

            if (!orderId) {
                alert("Vui lòng chọn một đơn hàng!");
                return;
            }

            $.ajax({
                url: "includes/customer-action/ajax/update_status.php",
                method: "POST",
                data: {
                    selected_order_id: orderId,
                    action: action
                },
                success: function(response) {
                    console.log("Response from server:", response);

                    try {
                        const res = response;
                        if (res.success) {
                            const statusText = {
                                '3': 'Order Shipped Out',
                                '4': 'Canceled',
                                '5': 'Order Received'
                            };

                            // Trạng thái theo cấp bậc (để so sánh logic)
                            const statusRank = {
                                'Waiting Confirm': 1,
                                'Order Paid': 2,
                                'Order Shipped Out': 3,
                                'Canceled': 4,
                                'Order Received': 5
                            };

                            const $row = $("input[name='selected_order_id']:checked").closest("tr");
                            const $statusCell = $row.find("td:nth-child(7)");
                            const currentStatusText = $statusCell.text().trim();
                            const currentStatusRank = statusRank[currentStatusText];
                            const newStatusRank = parseInt(res.new_status);
                            if (statusRank[currentStatusText] === 4) {
                                alert("Đơn hàng đã bị hủy, không thể cập nhật trạng thái!");
                                return;
                            }
                            // So sánh trạng thái mới phải cao hơn trạng thái hiện tại
                            if (newStatusRank > currentStatusRank) {
                                $statusCell.text(statusText[res.new_status]);
                                alert("Cập nhật thành công!");
                            } else {
                                alert("Không thể cập nhật trạng thái!");
                            }
                        } else {
                            alert("Cập nhật thất bại!");
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        alert("Lỗi xử lý phản hồi.");
                    }
                },
                error: function(xhr, status, error) {
                    console.log("XHR:", xhr);
                    console.log("Status:", status);
                    console.log("Error:", error);
                    alert("Lỗi gửi yêu cầu!");
                }
            });
        });
    });
    // loc don hang
    $(document).ready(function() {
        // Lọc đơn hàng theo trạng thái, ngày tạo, phường, quận, thành phố
        $("#filter-form").submit(function(event) {
            event.preventDefault(); // Ngăn chặn hành động gửi form mặc định

            const formData = $(this).serialize(); // Lấy tất cả dữ liệu từ form

            $.ajax({
                url: "includes/customer-action/ajax/filter_orders.php",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    console.log("Response from server:", response);
                    try {

                        if (response.success) {
                            // Cập nhật bảng với các đơn hàng mới
                            $("#order-table-body").html(response.orders_html);
                        } else {
                            alert("Không tìm thấy đơn hàng nào!");
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        alert("Lỗi xử lý phản hồi.");
                    }
                },
                error: function(xhr, status, error) {
                    console.log("XHR:", xhr);
                    console.log("Status:", status);
                    console.log("Error:", error);
                    alert("Lỗi gửi yêu cầu!");
                }
            });
        });
    });

    $(document).on('click', '.btn-view', function(e) {
    e.preventDefault();

    const orderId = $(this).data('id'); // Lấy ID từ thuộc tính data-id
    const url = 'includes/customer-action/ajax/order_detail.php?id=' + orderId;

    $.ajax({
        url: url,
        method: 'GET',
        success: function(html) {
            $('#modal-body').html(html); // Gán nội dung vào modal
            $('#order-modal').fadeIn(); // Hiển thị modal
        },
        error: function() {
            $('#modal-body').html('<p>Lỗi khi tải dữ liệu.</p>');
            $('#order-modal').fadeIn();
        }
    });
    });
</script>