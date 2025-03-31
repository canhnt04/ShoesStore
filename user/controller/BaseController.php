<?php
    class BaseController
    {
        function render($file, $data = array())
        {
            // Kiểm tra file gọi đến có tồn tại hay không?
            $view_file = __DIR__ . '/../view/' . $file . '.php';
            if (is_file($view_file)) {
            // Nếu tồn tại file đó thì tạo ra các biến chứa giá trị truyền vào lúc gọi hàm
            extract($data);
            // Sau đó lưu giá trị trả về khi chạy file view template
            // với các dữ liệu đó vào 1 biến chứ chưa hiển thị luôn ra trình duyệt
            ob_start();
            require_once($view_file);
            $content = ob_get_clean();

            } else {
            // Nếu file muốn gọi ra không tồn tại thì chuyển hướng đến trang báo lỗi.
            header('Location: index.php?controller=pages&action=error');
            }
        }
    }
?>
