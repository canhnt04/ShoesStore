$(document).ready(function () {
  function loadAjax(url, method = "GET", data = {}) {
    $.ajax({
      url: url,
      method: method,
      dataType: "html",
      data: data,
      beforeSend: function () {
        $(window).scrollTop(0);
        $("#ajaxLoad").html(`
                    <div style="height:600px; display:flex; align-items:center; justify-content:center;">
                        <img style="height:75px; width:75px; border-radius:50%; margin-top: -100px" 
                            src="/ShoesStore/public/assets/images/loading.gif" alt="Loading..." />
                    </div>
                `);
      },
      success: function (data) {
        $("#ajaxLoad").html(data);
        history.pushState({}, "", url);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        try {
          var errorMessage = JSON.parse(xhr.responseText);
          alert(errorMessage.message);
        } catch (e) {
          alert(thrownError);
        }
      },
    });
  }

  function callAPI(url, method = "GET", data = {}) {
    $.ajax({
      url: url,
      method: method,
      dataType: "html",
      data: data,
      success: function (data) {
        alert("Thêm giỏ hàng thành công");
      },
      error: function (xhr, ajaxOptions, thrownError) {
        try {
          var errorMessage = JSON.parse(xhr.responseText);
          alert(errorMessage.message);
        } catch (e) {
          alert(thrownError);
        }
      },
    });
  }

  function submitForm(url, method = "POST", data = {}) {
    $.ajax({
      url: url,
      method: method,
      dataType: "html",
      data: data,
      beforeSend: function () {
        $(window).scrollTop(0);
        $("#ajaxLoad").html(`
                    <div style="height:600px; display:flex; align-items:center; justify-content:center;">
                        <img style="height:75px; width:75px; border-radius:50%; margin-top: -100px" 
                            src="/ShoesStore/public/assets/images/loading.gif" alt="Loading..." />
                    </div>
                `);
      },
      success: function (data) {
        $("#ajaxLoad").html(data);
        history.pushState({}, "", url);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        // var errorMessage = JSON.parse(xhr.responseText);
        // alert(errorMessage.message);
      },
    });
  }

  function submitFormProfile(url, data = {}) {
    $.ajax({
      url: url,
      method: "POST",
      dataType: "html",
      data: data,
      beforeSend: function () {
        $(window).scrollTop(0);
        $("#ajaxLoad").html(`
                    <div style="height:600px; display:flex; align-items:center; justify-content:center;">
                        <img style="height:75px; width:75px; border-radius:50%; margin-top: -100px" 
                            src="/ShoesStore/public/assets/images/loading.gif" alt="Loading..." />
                    </div>
                `);
      },
      success: function (data) {
        $("#ajaxLoad").html(data);
        // $("input[name='fullname']").attr(
        //   "data-old",
        //   $("input[name='fullname']").val()
        // );
        // $("input[name='phone']").attr(
        //   "data-old",
        //   $("input[name='phone']").val()
        // );
        // $("input[name='address']").attr(
        //   "data-old",
        //   $("input[name='address']").val()
        // );
        // history.pushState({}, "", url);
        alert(123);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        try {
          var errorMessage = JSON.parse(xhr.responseText);
          alert(errorMessage.message);
        } catch (e) {
          alert("Lỗi không xác định: " + thrownError);
          console.error("Chi tiết lỗi:", xhr.responseText);
        }
      },
    });
  }

  function logOut(url) {
    $.ajax({
      url: url,
      dataType: "html",
      success: function (data) {
        $("#ajaxLoad").html(data);
        // Client -> Ajax catch -> Route -> controller -> Ajax sucsess -> Client
        window.location.href =
          "index.php?page=Product&action=showList&pageNumber=1"; // Trick bẩn reload trang
        history.pushState(
          {},
          "",
          "index.php?page=Product&action=showList&pageNumber=1"
        );
      },
      error: function (xhr, ajaxOptions, thrownError) {
        // var errorMessage = JSON.parse(xhr.responseText);
        // alert(errorMessage.message);
      },
    });
  }

  // GET
  $(document).on("click", ".ajaxLink", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    loadAjax(url);
  });

  // Search product
  $(document).on("click", "#btnSearch", function (e) {
    e.preventDefault();

    let baseUrl = $(this).data("url");
    const keyword = $("#searchInput").val().trim();
    const brand = $("select[name='brand']").val();
    const price = $("select[name='price']").val();
    let url = new URL(baseUrl, window.location.origin);
    url.searchParams.set("pageNumber", 1);
    if (keyword) url.searchParams.set("keyword", keyword);
    if (brand) url.searchParams.set("brand", brand);
    if (price) url.searchParams.set("price", price);

    loadAjax(url.toString());
  });

  // Update info profile
  $(document).on("submit", "#form-info", function (e) {
    e.preventDefault();

    const fullnameInput = $("input[name='fullname']");
    const phoneInput = $("input[name='phone']");
    const addressInput = $("input[name='address']");

    const fullname = fullnameInput.val().trim();
    const phone = phoneInput.val().trim();
    const address = addressInput.val().trim();

    const oldFullname = fullnameInput.attr("data-old")?.trim();
    const oldPhone = phoneInput.attr("data-old")?.trim();
    const oldAddress = addressInput.attr("data-old")?.trim();

    if (
      fullname === oldFullname &&
      phone === oldPhone &&
      address === oldAddress
    ) {
      alert("Bạn chưa thay đổi thông tin nào.");
      return;
    }

    if (fullname === "" || phone === "" || address === "") {
      alert("Vui lòng điền đầy đủ thông tin.");
      return;
    }

    const phoneRegex = /^0[0-9]{9}$/;
    if (!phoneRegex.test(phone)) {
      alert(
        "Số điện thoại không hợp lệ! Số điện thoại bắt đầu từ 0 và gồm 10 chữ số."
      );
      return;
    }

    const url = $(this).data("url");
    let data = $(this).serialize();
    console.log(data);
    submitFormProfile(url, data);
  });

  $(document).on("click", "#showById", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    loadAjax(url, "GET", data);
  });

  $(document).on("click", "#addToCartBtn", function (e) {
    e.preventDefault();

    const url = $(this).attr("href");
    const productId = $("#pr_id").val();
    const productDetailId = $("#prdetail_id").val();
    const size = $("#product-size").val();
    const quantity = $("#product-quanity").val();

    const data = {
      pr_id: productId,
      prdetail_id: productDetailId,
      product_size: size,
      product_quantity: quantity,
    };
    console.log("==> Gửi yêu cầu Add To Cart với dữ liệu:", data);

    // Gửi yêu cầu POST
    callAPI(url, "POST", data);
  });

  $(document).on("click", "#btnCheckout", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");

    const checked = document.querySelectorAll('input[type="checkbox"]:checked');
    selected = Array.from(checked).map((x) => ({
      product_id: x.getAttribute("product_id"),
      detail_id: x.getAttribute("detail_id"),
      quantity: x.getAttribute("product_quantity"),
      price: x.getAttribute("product_price"),
      color: x.getAttribute("product_color"),
      product_name: x.getAttribute("product_name"),
    }));

    console.log(selected);
    if (selected.length == 0) {
      alert("Please select one product to continue.");
      return;
    }

    loadAjax(url, "POST", { products: JSON.stringify(selected) });
  });

  $(document).on("submit", "#buyProductForm", function (e) {
    e.preventDefault();
    const url = $(this).attr("action");

    let data = $(this).serialize();
    console.log(data);
    loadAjax(url, "POST", data);
  });

  $(document).on("submit", "#submitForm", function (e) {
    e.preventDefault();
    const url = $(this).attr("action");

    let data = $(this).serialize(); // Lấy hết dữ liệu post trong form
    submitForm(url, "POST", data);
  });

  $(document).on("click", "#headerLogout", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    if (confirm("Confrim logout?")) {
      logOut(url);
    }
  });

  // Khi bấm nút back/forward trình duyệt
  window.onpopstate = function () {
    loadAjax(location.href);
  };

  // Khi bấm reload
  window.onload = function () {
    loadAjax(location.href);
  };
});
