<head>
    <link rel="stylesheet" href="/ShoesStore/public/assets/css/reset.css">
    <link rel="stylesheet" href="/ShoesStore/public/assets/css/auth.css">
    <link rel="stylesheet" href="/ShoesStore/public/assets/fonts/fontawesome-free-6.7.2-web/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,700&display=swap"
        rel="stylesheet">
</head>

<div id="ajaxLink">
    <div class="auth">
        <div class="auth__body">
            <div class="auth-form" id="container">
                <div class="auth-form__container sign-up">
                    <!-- Form đăng ký -->
                    <form class="auth-form__form" id="registerForm" method="POST">
                        <h1 class="auth-form__heading">ĐĂNG KÝ</h1>
                        <span class="auth-form__title">Tạo tài khoản sử dụng cho riêng bạn</span>
                        <input type="text" class="auth-form__input" name="new-username" placeholder="Nhập tên đăng nhập">
                        <input type="email" class="auth-form__input" name="new-email" placeholder="Nhập email">
                        <input type="password" class="auth-form__input" name="new-password" placeholder="Nhập mật khẩu">
                        <input type="password" class="auth-form__input" name="new-repassword" placeholder="Nhập lại mật khẩu">
                        <span class="auth-form__policy">Bằng việc tạo tài khoản, bạn đã đồng ý với
                            <a href="#" class="auth-form__link">Điều khoản dịch vụ</a> và
                            <a href="#" class="auth-form__link">Chính sách bảo mật</a>
                        </span>
                        <div class="auth-form__control">
                            <button type="submit" class="btn">ĐĂNG KÝ</button>
                            <button type="button" class="btn auth-form__control-back btn-back">QUAY LẠI</button>
                        </div>
                    </form>
                </div>
                <!-- Form đăng nhập -->
                <div class="auth-form__container sign-in">
                    <form class="auth-form__form" id="loginForm" method="POST">
                        <h1 class="auth-form__heading">ĐĂNG NHẬP</h1>
                        <span class="auth-form__title">Đăng nhập vào tài khoản của bạn</span>
                        <input type="text" class="auth-form__input" name="username" placeholder="Nhập tên đăng nhập">
                        <input type="password" class="auth-form__input" name="password" placeholder="Nhập mật khẩu">
                        <div class="auth-form__control">
                            <button type="submit" class="btn">ĐĂNG NHẬP</button>
                            <button type="button" class="btn auth-form__control-back btn-back">QUAY LẠI</button>
                        </div>
                    </form>
                </div>
                <!-- Toggle -->
                <div class="toggle-container">
                    <div class="toggle">
                        <div class="toggle-panel toggle-item__left">
                            <h1 class="toggle-panel__heading">CHÀO MỪNG!</h1>
                            <span class="toggle-panel__title">Đăng nhập ngay để không bỏ lỡ những cập nhật và ưu đãi mới nhất dành riêng cho bạn!</span>
                            <button class="btn hidden" id="toggleLogin">ĐĂNG NHẬP</button>
                        </div>
                        <div class="toggle-panel toggle-item__right">
                            <h1 class="toggle-panel__heading">XIN CHÀO!</h1>
                            <span class="toggle-panel__title">Tạo tài khoản để trải nghiệm dịch vụ tốt nhất và nhận nhiều phần quà hấp dẫn.</span>
                            <button class="btn hidden" id="toggleRegister">ĐĂNG KÝ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/ShoesStore/public/assets/js/jquery-1.11.0.min.js"></script>
<script src="/ShoesStore/public/assets/js/jquery-3.7.1.min.js"></script>
<script src="/ShoesStore/public/assets/js/ajax.js"></script>
<script src="/ShoesStore/public/assets/js/auth.js"></script>