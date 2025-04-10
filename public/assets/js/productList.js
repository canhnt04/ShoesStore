$(document).ready(function () {

    function loadProductPage(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'html',
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

    // Gắn sự kiện click cho link "Shop"
    $(document).on('click', 'a.nav-link[href*="page=Product&action=showList"]', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        loadProductPage(url);
    });

    // Gắn sự kiện click cho phân trang
    $(document).on('click', '.paginationProduct a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        console.log("Pagination URL:", url);
        loadProductPage(url);
    });

});