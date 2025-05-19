$(document).ready(function () {
  function loadImports(page = 1) {
    const formData = $("#import-form").serialize();
    const dataSend = formData + "&page=" + page;

    $.ajax({
      url: "ajax-handler/importreceipt/filter_imports.php",
      method: "GET",
      data: dataSend,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#error-message").html("");
          $("#order-table-body").html(response.tbody);
          $("#pagination").html(response.pagination);
          $("#pagination .page-link")
            .off("click")
            .on("click", function (e) {
              e.preventDefault();
              const selectedPage = $(this).data("page");
              loadImports(selectedPage);
            });
        } else {
          $("#error-message").html(response.message || "Không có dữ liệu.");
          $("#order-table-body").html("");
          $("#pagination").html("");
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", xhr.responseText);
        alert("Lỗi khi tải dữ liệu phiếu nhập.");
      },
    });
  }

  $("#import-form").on("submit", function (e) {
    e.preventDefault();
    loadImports(1);
  });

  loadImports(1);


  $(document).on("click", ".btn-view", function (e) {
    e.preventDefault();

    const importId = $(this).data("id");

    $.ajax({
      url: "ajax-handler/importreceipt/get_import_detail.php",
      method: "GET",
      data: {
        id: importId,
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
});
