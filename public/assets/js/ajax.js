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
                            src="/public/assets/images/loading.gif" alt="Loading..." />
                    </div>
                `);
      },
      success: function (data) {
        $("#ajaxLoad").html(data);
        history.pushState({}, "", url);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        var errorMessage = JSON.parse(xhr.responseText);
        alert(errorMessage.message);
      },
    });
  }

  function callAPI(url, method = "GET", data = {}) {
    $.ajax({
      url: url,
      method: method,
      dataType: "json",
      data: data,
      success: function (data) {
        console.log(data);
        alert(data.message);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        var errorMessage = JSON.parse(xhr.responseText);
        alert(errorMessage.message);
      },
    });
  }

  function submitForm(url, method = "GET", data = {}) {
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
                            src="/public/assets/images/loading.gif" alt="Loading..." />
                    </div>
                `);
      },
      success: function (data) {
        // $('#ajaxLoad').html("<p>Hello World</p>");
        $("#ajaxLoad").html(data);
        alert("Your order is successful!");
        history.pushState({}, "", "Route.php?page=Home&action=index");
      },
      error: function (xhr, ajaxOptions, thrownError) {
        var errorMessage = JSON.parse(xhr.responseText);
        alert(errorMessage.message);
      },
    });
  }

  // GET
  $(document).on("click", ".ajaxLink", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");
    loadAjax(url);
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

  // Khi bấm nút back/forward trình duyệt
  window.onpopstate = function () {
    loadAjax(location.href);
  };

  // Khi bấm reload
  window.onload = function () {
    loadAjax(location.href);
  };
});
