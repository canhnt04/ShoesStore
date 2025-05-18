$(document).ready(function () {
  $(document).ready(function () {
    function loadSuppliers() {
      $.ajax({
        url: "ajax-handler/importreceipt/filter_supplier.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            const select = $("#supplier-select");
            select.empty(); // Xóa các tùy chọn hiện tại
            select.append('<option value="">-- Chọn nhà cung cấp --</option>');
            response.suppliers.forEach(function (supplier) {
              select.append(
                `<option value="${supplier.id}">${supplier.name}</option>`
              );
            });
          } else {
            alert("Lỗi khi tải danh sách nhà cung cấp");
          }
        },
        error: function () {
          alert("Có lỗi xảy ra khi gọi AJAX");
        },
      });
    }

    loadSuppliers();
  });
  // phân trang sản phẩm
  $(document).ready(function () {
    function loadProducts(page = 1) {
      $.ajax({
        url: "ajax-handler/importreceipt/filter_product.php",
        type: "GET",
        data: { page: page },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#body-product").html(response.tbody);
            $("#product-pagination").html(response.pagination);
          } else {
            alert("Lỗi khi tải danh sách sản phẩm");
          }
        },
        error: function () {
          alert("Có lỗi xảy ra khi gọi AJAX");
        },
      });
    }

    // Tải sản phẩm khi trang được tải
    loadProducts();

    // Xử lý sự kiện click trên phân trang
    $("#product-pagination").on("click", ".page-link", function (e) {
      e.preventDefault();
      const page = $(this).data("page");
      if (page) {
        loadProducts(page);
      }
    });
  });
  $("#body-product").on("click", ".btn-choose-product", function () {
    let productId = $(this).data("id");

    $.ajax({
      url: "ajax-handler/importreceipt/filter_product_detail.php",
      method: "GET",
      data: { product_id: productId },
      dataType: "json",
      success: function (res) {
        if (res.success) {
          $("#variant-table-body").html(res.tbody);
        } else {
          alert("Không tìm thấy chi tiết sản phẩm");
          $("#variant-table-body").html("");
        }
      },
      error: function () {
        alert("Lỗi khi lấy chi tiết sản phẩm");
      },
    });
  });

  let selectedDetailId = null;

  $("#variant-table-body").on("click", ".add-to-import", function () {
    const $button = $(this);
    selectedDetailId = $button.data("id");

    // Đổi nút thành "Đã chọn"
    $("#variant-table-body .add-to-import")
      .text("Chọn")
      .prop("disabled", false)
      .removeClass("selected");
    $button.text("Đã chọn").prop("disabled", true).addClass("selected");
  });

  $("#add-detail-btn").on("click", function () {
    const quantity = parseInt($("#quantity").val());
    const price = parseInt($("#price").val());

    if (!selectedDetailId) {
      alert("Vui lòng chọn chi tiết sản phẩm trước.");
      return;
    }

    if (!quantity || quantity < 1 || !price || price < 0) {
      alert("Vui lòng nhập số lượng và giá hợp lệ.");
      return;
    }

    // Kiểm tra nếu đã thêm rồi
    if ($(`#details-table-body tr[data-id="${selectedDetailId}"]`).length > 0) {
      alert("Chi tiết sản phẩm đã được thêm.");
      return;
    }

    const newRow = `
          <tr data-id="${selectedDetailId}">
            <td></td>
            <td>${selectedDetailId}</td>
            <td>${quantity}</td>
            <td>${price.toLocaleString()}</td>
            <td><button class="remove-detail">Xóa</button></td>
          </tr>
        `;

    $("#details-table-body").append(newRow);
    updateDetailRowIds();

    // Reset chọn
    selectedDetailId = null;
    $("#variant-table-body .add-to-import")
      .text("Chọn")
      .prop("disabled", false)
      .removeClass("selected");
  });
  $("#details-table-body").on("click", ".remove-detail", function () {
    const row = $(this).closest("tr");
    const productDetailId = row.data("id");

    row.remove();
    updateDetailRowIds();

    const $button = $(
      `#variant-table-body button[data-id="${productDetailId}"]`
    );
    $button.text("Chọn").prop("disabled", false).removeClass("selected");

    // Nếu đang chọn cái bị xóa thì reset lại biến
    if (selectedDetailId === productDetailId) {
      selectedDetailId = null;
    }
  });

  function updateDetailRowIds() {
    $("#details-table-body tr").each(function (index) {
      $(this)
        .find("td:first")
        .text(index + 1);
    });
  }
  // Form lưu phiếu nhập
  $("#import-form").on("submit", function (e) {
    e.preventDefault();

    const supplierId = $("#supplier-select").val();
    if (!supplierId) {
      alert("Vui lòng chọn nhà cung cấp");
      return;
    }

    const details = [];
    $("#details-table-body tr").each(function () {
      const tds = $(this).find("td");
      details.push({
        product_detail_id: tds.eq(1).text(),
        quantity: parseInt(tds.eq(2).text()),
        price: parseFloat(tds.eq(3).text().replace(/[. ]+/g, "")),
      });
    });

    if (details.length === 0) {
      alert("Vui lòng thêm chi tiết sản phẩm");
      return;
    }

    $.ajax({
      url: "ajax-handler/importreceipt/save_import.php",
      method: "POST",
      data: {
        supplier_id: supplierId,
        details: JSON.stringify(details),
      },
      dataType: "json",
      success: function (res) {
        if (res.success) {
          alert("Lưu phiếu nhập thành công! Mã phiếu: " + res.import_id);
          $("#supplier-select").val("");
          $("#quantity").val(1);
          $("#price").val("");
          $("#details-table-body").empty();
          $("#variant-table-body").empty();
        } else {
          alert("Lỗi: " + res.message);
        }
      },
      error: function () {
        alert("Lỗi khi gửi yêu cầu");
      },
    });
  });
});
