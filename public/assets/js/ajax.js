$(document).ready(function () {

    function loadAjax(url, method = "GET", data = {}) {
        $.ajax({
            url: url,
            method: method,
            dataType: "html",
            data: data,
            beforeSend: function () {  
                $('#ajaxLoad').html('<p style="height:400px;">Đang tải dữ liệu...</p>');
            },
            success: function (data) {
                // $('#ajaxLoad').html("<p>Hello World</p>");
                $('#ajaxLoad').html(data);
                history.pushState({}, '', url);
            },
            error: function () {
                alert('Lỗi khi tải dữ liệu.');
            }
        });
    }

    $(document).on('click', 'a:not(#addToCartBtn)', function (e) {
        const url = $(this).attr('href');

        if(url.startsWith('#')) {
            console.log("==> Các thẻ <a> có #: ");
            return;
        }

        if(url.includes('page=') && url.includes('action=')) {
            e.preventDefault();
            console.log("==> Bắt được link cần AJAX: " + url);
            loadAjax(url);
        }
        else{
            // Chuyển hướng thông thường. VD: facebook.com,...
            window.location = location.href;
        }
    });

    // Xử lý riêng cho nút Add To Cart
    $(document).on('click', '#addToCartBtn', function(e) {
        e.preventDefault();
        
        const url = $(this).attr('href');
        
        const productId = $("#pr_id").val();
        const productDetailId = $("#prdetail_id").val();
        const size = $("#product-size").val();
        const quantity = $("#product-quanity").val();

        const data = {
            pr_id: productId,
            prdetail_id: productDetailId,
            product_size: size,
            product_quantity: quantity
        };
        
        console.log("==> Gửi yêu cầu Add To Cart với dữ liệu:", data);
        
        // Gửi yêu cầu POST
        loadAjax(url, "POST", data);
    });

    // Khi bấm nút back/forward trình duyệt
    window.onpopstate = function () {
        loadAjax(location.href);
    };
});