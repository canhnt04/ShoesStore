<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê khách hàng</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h2>Thống kê khách hàng</h2>

    <form id="statistic-form" method="GET">
        <label>Từ ngày:</label>
        <input type="datetime-local" name="begin_date" required>

        <label>Đến ngày:</label>
        <input type="datetime-local" name="end_date" required>

        <label>Sắp xếp:</label>
        <select name="sort_order">
            <option value="desc">Giảm dần</option>
            <option value="asc">Tăng dần</option>
        </select>

        <button type="submit">Thống kê</button>
    </form>

    <div id="statistic-result">

    </div>

    <canvas id="customerChart" width="400" height="200"></canvas>
    <div id="order-modal" style="display:none;" class="modal">
        <div class="modal-content">
            <button onclick="$('#order-modal').hide()">Đóng</button>
            <div id="modal-body"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- phải đặt trước đoạn JS -->

    <script>
        let chartInstance = null; // Biến toàn cục để lưu biểu đồ

        $(document).ready(function() {
            $("#statistic-form").submit(function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: "includes/customer-action/ajax/stats.php",
                    method: "GET",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $("#statistic-result").html(response.html);

                            // Hủy biểu đồ cũ nếu có
                            if (chartInstance) {
                                chartInstance.destroy();
                            }

                            const ctx = document.getElementById("customerChart").getContext("2d");

                            chartInstance = new Chart(ctx, {
                                type: "bar",
                                data: {
                                    labels: response.chartData.labels,
                                    datasets: [{
                                        label: "Tổng mua",
                                        data: response.chartData.data,
                                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                                        borderColor: "rgba(54, 162, 235, 1)",
                                        borderWidth: 1,
                                    }, ],
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                        },
                                    },
                                },
                            });
                        } else {
                            $("#statistic-result").html("<p>" + response.message + "</p>");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Ajax Error:", xhr.responseText);
                        $("#statistic-result").html("<p>Lỗi khi gửi yêu cầu thống kê.</p>");
                    },
                });
            });
        });
        $(document).on('click', 'a.view-orders', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            $.get(url, function(html) {
                $('#modal-body').html(html);
                $('#order-modal').fadeIn();
            }).fail(function() {
                $('#modal-body').html('<p>Lỗi khi tải dữ liệu.</p>');
                $('#order-modal').fadeIn();
            });
        });
    </script>
</body>

</html>