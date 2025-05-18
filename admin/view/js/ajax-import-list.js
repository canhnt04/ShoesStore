$(document).ready(function () {
  function loadImports(page = 1) {
    const beginDate = $("#begin_date").val();
    const endDate = $("#end_date").val();

    $.ajax({
      url: "ajax-handler/importreceipt/filter_imports.php",
      method: "GET",
      dataType: "json",
      data: {
        page: page,
        begin_date: beginDate,
        end_date: endDate,
      },
      success: function (response) {
        if (response.success) {
          console.log(response);

          $("#order-table-body").html(response.tbody);
          $("#pagination").html(response.pagination);
        } else {
          alert("Tải dữ liệu thất bại.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", status, error);
      },
    });
  }

  // Khi trang vừa load
  $(document).ready(function () {
    loadImports(); // tải trang đầu tiên

    // Sự kiện khi lọc
    $("#filter-form").on("submit", function (e) {
      e.preventDefault();
      loadImports(1);
    });

    // Sự kiện khi click vào các trang
    $("#pagination").on("click", "a.page-link", function (e) {
      e.preventDefault();
      const page = $(this).data("page");
      if (page) {
        loadImports(page);
      }
    });
  });
  // lấy danh sách ncc
});
