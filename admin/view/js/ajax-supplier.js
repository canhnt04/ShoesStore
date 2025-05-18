$(document).ready(function () {
  function loadSuppliers(page = 1, name = "") {
    $.ajax({
      url: "ajax-handler/supplier/getListSupplier.php", // sửa lại đường dẫn file PHP đúng
      type: "GET",
      data: {
        page: page,
        name: name.trim(),
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#supplier-table-body").html(response.tbody);
          $("#pagination").html(response.pagination);
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

  $("#search-supplier-btn").on("click", function () {
    const name = $("#search-supplier-input").val();
    loadSuppliers(1, name);
  });

  $("#pagination").on("click", ".page-link", function (e) {
    e.preventDefault();
    const page = $(this).data("page");
    const name = $("#search-supplier-input").val();
    if (page) {
      loadSuppliers(page, name);
    }
  });

  $("#supplier-table-body").on("click", ".btn-view-supplier", function () {
    const supplierId = $(this).data("id");
    console.log("Xem chi tiết nhà cung cấp ID:", supplierId);
  });
});
