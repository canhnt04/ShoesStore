<?php
if (isset($_SESSION['message'])): ?>

    <div id="alert" class="alert <?php echo $_SESSION['message_type']; ?>">
        <?php
        if ($_SESSION['message_type'] == 'success') {
            echo '<i class="fa-solid fa-circle-check"></i>';
        } else {
            echo '<i class="fa-solid fa-circle-xmark"></i>';
        }
        ?>
        <span style="margin-left: 6px"><?php echo $_SESSION['message']; ?></span>
    </div>

    <script>
        setTimeout(function() {
            var alertBox = document.getElementById('alert');
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s"; // Hiệu ứng mờ dần
                alertBox.style.opacity = "0"; // Giảm opacity về 0
                setTimeout(function() {
                    alertBox.style.display = "none"; // Ẩn hẳn khỏi giao diện
                }, 500);
            }
        }, 2000); // 2 giây
    </script>

    <?php
    // Xóa session sau khi hiển thị
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>
<?php endif; ?>