$(document).ready(function () {

    function loadAjax(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'html',
            beforeSend: function () {  
                $('#ajaxLoad').html('<p style="height:400px;">Đang tải dữ liệu...</p>');
            },
            success: function (data) {
                // $('#ajaxLoad').html("<p>Hello World</p>");
                $('#ajaxLoad').replaceWith(data);
                history.pushState({}, '', url);
            },
            error: function () {
                alert('Lỗi khi tải dữ liệu.');
            }
        });
    }

    $(document).on('click', 'a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if(url.startsWith('#')) return;
        console.log("==> Bắt được link: " + url);
        loadAjax(url);
    });

    // Khi bấm nút back/forward trình duyệt
    window.onpopstate = function () {
        loadAjax(location.href);
    };
});