$(document).ready(function () {
  $(document).ready(function () {
    function loadOrders(page = 1) {
      // Lấy dữ liệu từ form lọc
      const formData = $("#filter-form").serialize();

      // Thêm page vào dữ liệu gửi đi
      const dataToSend = formData + "&page=" + page;
      console.log(dataToSend);

      $.ajax({
        url: "ajax-handler/order/filter_orders.php",
        method: "GET",
        data: dataToSend,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#order-table-body").html(response.tbody);
            $("#pagination").html(response.pagination);

            // Bắt lại sự kiện click phân trang
            $("#pagination .page-link")
              .off("click")
              .on("click", function (e) {
                e.preventDefault();
                const selectedPage = $(this).data("page");
                loadOrders(selectedPage); // Gọi lại AJAX với page mới
              });
          } else {
            alert(response.message || "Không thấy đơn hàng nào.");
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX lỗi:", xhr.responseText);
          alert("Lỗi khi tải dữ liệu đơn hàng.");
        },
      });
      
    }
    $("#filter-form").on("submit", function (e) {
      e.preventDefault();
      loadOrders(1);
    });

    // Load dữ liệu trang đầu tiên khi load trang
    loadOrders(1);
  });

  // Xem chi tiết đơn hàng
  $(document).on("click", ".btn-view", function (e) {
    e.preventDefault();

    const orderId = $(this).data("id"); // Lấy ID từ thuộc tính data-id

    $.ajax({
      url: "ajax-handler/order/get_order_detail.php",
      method: "GET",
      data: {
        id: orderId,
      },
      dataType: "html",
      success: function (html) {
        $("#modal-body").html(html); // Gán nội dung vào modal
        $("#order-modal").fadeIn(); // Hiển thị modal
      },
      error: function () {
        $("#modal-body").html("<p>Lỗi khi tải dữ liệu.</p>");
        $("#order-modal").fadeIn();
      },
    });
  });

  // Cập nhật trạng thái đơn hàng
  $(document).ready(function () {
    let selectedOrderId = null;
    let selectedAction = null;
    // Bắt sự kiện khi click các nút trong form
    $("#actionForm button")
      .off("click")
      .on("click", function () {
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
          $.post(
            "ajax-handler/order/get_quantity_product.php",
            {
              order_id: orderId,
            },
            function (res) {
              if (res.success) {
                console.log("Dữ liệu trả về từ server:", res);

                let html = `<ul>`;

                let hasInsufficientStock = false;
                res.order.forEach((item) => {
                  const isInsufficient =
                    item.ordered_quantity > item.product_quantity;
                  if (isInsufficient) hasInsufficientStock = true;
                  html += `<li style="color: ${
                    isInsufficient ? "red" : "black"
                  };">
                  ${item.product_name}: ${item.ordered_quantity} sản phẩm 
                  (Còn lại: ${item.product_quantity})
                  ${isInsufficient ? " <strong>Không đủ hàng!</strong>" : ""}
                 </li>`;
                });

                html += "</ul>";
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
            },
            "json"
          );
        } else if (action === "cancel_order" || action === "confirm_delivery") {
          // Với hủy hoặc xác nhận giao: gọi luôn, không qua modal
          sendUpdateRequest(orderId, action);
        }
      });

    $("#confirmApproveBtn")
      .off("click")
      .on("click", function () {
        $("#approveOrderModal").fadeOut();
        sendUpdateRequest(selectedOrderId, selectedAction);
      });
    $("#cancelApproveBtn, #closeModalBtn")
      .off("click")
      .on("click", function () {
        $("#approveOrderModal").fadeOut();
      });
  });

  function sendUpdateRequest(orderId, action) {
    $.ajax({
      url: "ajax-handler/order/update_status.php",
      method: "POST",
      data: {
        selected_order_id: orderId,
        action: action,
      },
      success: function (response) {
        console.log("Raw response:", response);
        const res =
          typeof response === "string" ? JSON.parse(response) : response;
        console.log("Parsed response:", res);
        if (res.success) {
          const statusText = {
            3: "Order Shipped Out",
            4: "Order Canceled",
            5: "Order Received",
          };
          const statusRank = {
            "Order Placed": 1,
            "Order Paid": 2,
            "Order Shipped Out": 3,
            "Order Canceled": 4,
            "Order Received": 5,
          };

          const $row = $("input[name='selected_order_id']:checked").closest(
            "tr"
          );
          const $statusCell = $row.find("td:nth-child(7)");
          const currentStatusText = $statusCell.text().trim();
          const currentStatusRank = statusRank[currentStatusText];
          const newStatusRank = parseInt(res.new_status);
          if (
            (newStatusRank === 4 || newStatusRank === 5) &&
            currentStatusRank < 3
          ) {
            alert(
              "Không thể cập nhật sang 'Đã giao hoặc Đã hủy' khi đơn hàng chưa được duyệt!"
            );
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
      error: function () {
        alert("Lỗi gửi yêu cầu!");
      },
    });
  }
  $("#confirmApproveBtn").click(function () {
    const orderId = $("#approveOrderModal").data("order-id");
    const action = $("#approveOrderModal").data("action");
    sendUpdateRequest(orderId, action);
  });
});
