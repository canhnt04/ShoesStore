<?php
include_once __DIR__ . '/../../../controller/OrderController.php';
include_once __DIR__ . '/../alert_message.php';
$orderController = new OrderController();
$orders = $orderController->listOrders();
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

    <form id="filter-form" class="filter_orders" method="POST">
        <div class="form-group-order">
            <label for="status">Trạng thái</label>
            <select name="status" class="">
                <option value="">Tất cả trạng thái</option>
                <option value="1">Order Placed</option>
                <option value="2">Order Paid</option>
                <option value="3">Order Shipped Out</option>
                <option value="4">Canceled</option>
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
            <?php foreach ($orders as $data): ?>
                <tr>
                    <td><input type="radio" name="selected_order_id" value="<?= $data['order']->getId() ?>" form="actionForm"></td>
                    <td><?= htmlspecialchars($data['order']->getId()) ?></td>
                    <td><?= htmlspecialchars($data['order']->getUserId()) ?></td>
                    <td><?= htmlspecialchars($data['customer_name']) ?></td>
                    <td><?= htmlspecialchars($data['customer_phone']) ?></td>
                    <td><?= htmlspecialchars($data['customer_address']) ?></td>
                    <td><?= htmlspecialchars($data['status_name']) ?></td>
                    <td><?= htmlspecialchars($data['order']->getNote()) ?></td>
                    <td><?= isset($data['total_price']) ? number_format($data['total_price'], 0, ',', '.') . ' VND' : '-' ?></td>
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
<script>
    // thay doi trang thai
    $(document).ready(function() {
        let selectedOrderId = null;
        let selectedAction = null;

        // Bắt sự kiện khi click các nút trong form
        $("#actionForm button").off("click").on("click", function() {
            const action = $(this).data("action");
            const orderId = $("input[name='selected_order_id']:checked").val();

            if (!orderId) {
                alert("Vui lòng chọn một đơn hàng!");
                return;
            }
            const $row = $("input[name='selected_order_id']:checked").closest("tr");
            const currentStatus = $row.find("td:nth-child(7)").text().trim();
            const forbiddenStatuses = ["Order Canceled", "Order Received"];

            if (forbiddenStatuses.includes(currentStatus)) {
                alert("Đơn hàng đã duyệt hoặc đã hủy không thể duyệt lại!");
                return;
            }
            if (action === "approve_order") {
                $.post("ajax-php/get_quantity_product.php", {
                    order_id: orderId
                }, function(res) {
                    if (res.success) {
                        let html = `<ul>`;

                        let hasInsufficientStock = false;
                        res.order.forEach(item => {
                            const isInsufficient = item.ordered_quantity > item.product_quantity;
                            if (isInsufficient) hasInsufficientStock = true;
                            html += `<li style="color: ${isInsufficient ? 'red' : 'black'};">
            ${item.product_name}: ${item.ordered_quantity} sản phẩm 
            (Còn lại: ${item.product_quantity})
            ${isInsufficient ? ' <strong>Không đủ hàng!</strong>' : ''}
           </li>`;
                        });

                        html += '</ul>';
                        $("#modalOrderInfo").html(html);
                        selectedOrderId = orderId;
                        selectedAction = action;
                        if (hasInsufficientStock) {
                            $("#confirmApproveBtn").hide();
                        } else {
                            $("#confirmApproveBtn").show().text("Xác nhận duyệt");
                        }
                        $("#approveOrderModal").fadeIn();
                    } else {
                        alert("Không lấy được thông tin đơn hàng!");
                    }
                }, "json");
            } 
            else if (action === "cancel_order" || action === "confirm_delivery") {
                // Với hủy hoặc xác nhận giao: gọi luôn, không qua modal
                sendUpdateRequest(orderId, action);
            }
        });

        $("#confirmApproveBtn").off("click").on("click", function() {
            $("#approveOrderModal").fadeOut();
            sendUpdateRequest(selectedOrderId, selectedAction);
        });
        $("#cancelApproveBtn, #closeModalBtn").off("click").on("click", function() {
            $("#approveOrderModal").fadeOut();
        });

    });

    function sendUpdateRequest(orderId, action) {
        $.ajax({
            url: "ajax-php/update_status.php",
            method: "POST",
            data: {
                selected_order_id: orderId,
                action: action
            },
            success: function(response) {
                console.log("Raw response:", response);
                const res = typeof response === 'string' ? JSON.parse(response) : response;
                console.log("Parsed response:", res);
                if (res.success) {
                    const statusText = {
                        '3': 'Order Shipped Out',
                        '4': 'Order Canceled',
                        '5': 'Order Received'
                    };
                    const statusRank = {
                        'Order Placed': 1,
                        'Order Paid': 2,
                        'Order Shipped Out': 3,
                        'Order Canceled': 4,
                        'Order Received': 5
                    };

                    const $row = $("input[name='selected_order_id']:checked").closest("tr");
                    const $statusCell = $row.find("td:nth-child(7)");
                    const currentStatusText = $statusCell.text().trim();
                    const currentStatusRank = statusRank[currentStatusText];
                    const newStatusRank = parseInt(res.new_status);
                    if ((newStatusRank === 4 || newStatusRank === 5) && currentStatusRank < 3) {
                        alert("Không thể cập nhật sang 'Đã giao hoặc Đã hủy' khi đơn hàng chưa được duyệt!");
                        return;
                    }
                    if (currentStatusRank === 4) {
                        alert("Đơn hàng đã bị hủy, không thể cập nhật trạng thái!");
                        return;
                    }

                    if (newStatusRank > currentStatusRank) {
                        $statusCell.text(statusText[res.new_status]);
                        alert("Cập nhật thành công!");
                    } else {
                        alert("Không thể cập nhật trạng thái!");
                    }
                } else {
                    alert("err!");
                }
            },
            error: function() {
                alert("Lỗi gửi yêu cầu!");
            }
        });
    }
    $("#confirmApproveBtn").click(function() {
        const orderId = $("#approveOrderModal").data("order-id");
        const action = $("#approveOrderModal").data("action");
        sendUpdateRequest(orderId, action);
    });

    // loc don hang
    $(document).ready(function() {
        $("#filter-form").submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: "ajax-php/filter_orders.php",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    console.log("Response from server:", response);
                    try {

                        if (response.success) {

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

    // xem chi tiết
    $(document).on('click', '.btn-view', function(e) {
        e.preventDefault();

        const orderId = $(this).data('id'); // Lấy ID từ thuộc tính data-id

        $.ajax({
            url: "ajax-php/get_order_detail.php",
            method: 'GET',
            data: {
                id: orderId
            },
            dataType: "html",
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