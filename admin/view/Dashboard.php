<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<aside class="sidebar">
    <ul class="menu">
        <li class="module-manager <?php echo ($page == 'home') ? 'active' : ''; ?>">
            <a href="index.php?page=home"><i class="fas fa-home"></i> Trang chủ</a>
        </li>
        <li class="menu-title">QUẢN LÝ</li>
        <li class="module-manager <?php echo ($page == 'account_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=account_manager"><i class="fas fa-user"></i> Tài khoản & phân quyền</a>
        </li>
        <li class="module-manager <?php echo ($page == 'product_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=product_manager"><i class="fas fa-box"></i> Sản phẩm</a>
        </li>
        <li class="module-manager <?php echo ($page == 'staff_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=staff_manager"><i class="fas fa-users"></i> Nhân viên</a>
        </li>
        <li class="module-manager <?php echo ($page == 'customer_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=customer_manager"><i class="fas fa-user-friends"></i> Khách hàng</a>
        </li>
        <li class="menu-title">THỐNG KÊ</li>
        <li class="module-manager <?php echo ($page == 'stats_time') ? 'active' : ''; ?>">
            <a href="index.php?page=stats_customer"><i class="fas fa-chart-line"></i> Thống kê khách hàng</a>
        </li>
    </ul>
</aside>