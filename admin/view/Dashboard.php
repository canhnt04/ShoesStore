<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<aside class="sidebar">
    <ul class="menu">
        <li class="module-manager <?php echo ($page == 'home') ? 'active' : ''; ?>">
            <a href="index.php?page=home"><i class="fas fa-home"></i> Trang chủ</a>
        </li>
        <li class="menu-title">QUẢN LÝ</li>
        <li class="module-manager <?php echo ($page == 'product_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=product_manager"><i class="fas fa-box"></i> Sản phẩm</a>
        </li>
        <li class="module-manager <?php echo ($page == 'employee_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=employee_manager"><i class="fas fa-users"></i> Nhân viên</a>
        </li>
        <li class="module-manager <?php echo ($page == 'customer_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=customer_manager"><i class="fas fa-user-friends"></i> Khách hàng</a>
        </li>


        <li class="module-manager <?php echo ($page == 'import_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=import_manager"><i class="fa-solid fa-file-import"></i></i>Nhập hàng</a>
        </li>


        <li class="module-manager <?php echo ($page == 'supplier_manager') ? 'active' : ''; ?>">
            <a href="index.php?page=supplier_manager"><i class="fa-solid fa-truck-field"></i></i>Nhà cung cấp</a>
        </li>

        <li class="menu-title">THỐNG KÊ</li>

        <li class="module-manager <?php echo ($page == 'stats_customer') ? 'active' : ''; ?>">
            <a href="index.php?page=stats_customer"><i class="fas fa-chart-bar"></i> Thống kê khách hàng</a>
        </li>
    </ul>
</aside>