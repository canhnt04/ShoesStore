$(document).ready(function () {

    function loadProductPage(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'html',
            beforeSend: function () {
                // Chạy trước khi gửi request
                $('#loadProduct').html('<p style="height:400px;">Đang tải dữ liệu...</p>');
            },
            success: function (data) {
                // $('#loadProduct').html("<p>Hello World</p>");
                $('#loadProduct').html($(data).find('#loadProduct').html());
                history.pushState({}, '', url); // Cập nhật URL
            },
            error: function () {
                alert('Lỗi khi tải dữ liệu sản phẩm.');
            }
        });
    }

    // Bắt link trên trang ProductList.php, còn các link của các trang khác sẽ render toàn bộ trang.
    $(document).on('click', 'a[href*="page=Product&action=showList"], a[href*="page=Product&action=showByCategory"]', function (e) {
        e.preventDefault();
        console.log('Hành vi mặc định đã bị ngăn chặn');
        console.log('URL được chọn:', $(this).attr('href'));
        let url = $(this).attr('href');
        loadProductPage(url);
    });
    
});