<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<aside class="sidebar">
    <ul class="menu">
        <li class="module-manager <?php echo ($page == 'home') ? 'active' : ''; ?>">
            <a href="index.php?page=home"><i class="fas fa-home"></i> Trang chủ</a>
        </li>
        <li class="menu-title">QUẢN LÝ</li>
        <li class="module-manager <?php echo ($page == 'role_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=role_manager"><i class="fas fa-user"></i>Vai trò</a>
        </li>
        <li class="module-manager <?php echo ($page == 'product_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=product_manager"><i class="fas fa-box"></i> Sản phẩm</a>
        </li>
        <li class="module-manager <?php echo ($page == 'employee_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=employee_manager"><i class="fas fa-users"></i> Nhân viên</a>
        </li>
        <li class="module-manager <?php echo ($page == 'customer_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=customer_manager"><i class="fas fa-user-friends"></i> Khách hàng</a>
        </li>
        <li class="menu-title">THỐNG KÊ</li>

        <li class="module-manager <?php echo ($page == 'stats_customer') ? 'active' : ''; ?>">
            <a href="index.php?page=stats_customer"><i class="fas fa-chart-bar"></i> Thống kê khach hang</a>
        </li>
    </ul>
</aside>