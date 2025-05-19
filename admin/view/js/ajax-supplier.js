$(document).ready(function () {
  // Khi bấm nút tìm kiếm
  $("#btn-search").on("click", function () {
    loadSuppliers(1); // Luôn load lại trang đầu tiên khi tìm
  });

  // Khi bấm vào phân trang
  $(document).on("click", ".page-link", function (e) {
    e.preventDefault();
    const page = $(this).data("page");
    loadSuppliers(page);
  });

  function loadSuppliers(page = 1) {
    const name = $('input[name="name"]').val();

    $.get(
      "ajax-handler/supplier/getListSupplier.php", // Đường dẫn AJAX phải đúng
      { page, name },
      function (response) {
        if (response.success) {
          $("#supplier-table-body").html(response.tbody);
          $("#pagination").html(response.pagination);
        } else {
          $("#supplier-table-body").html(
            '<tr><td colspan="5">Không có dữ liệu.</td></tr>'
          );
        }
      },
      "json"
    );
  }

  loadSuppliers();
});
