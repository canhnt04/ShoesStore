<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$role = $_SESSION['role'] ?? null;
$userId = $_SESSION['userId'] ?? null;

echo $role;
?>

<aside class="sidebar">
    <ul class="menu">
        <li class="module-manager <?php echo ($page == 'home') ? 'active' : ''; ?>">
            <a href="index.php?page=home"><i class="fas fa-home"></i> Trang chủ</a>
        </li>

        <li class="menu-title">QUẢN LÝ</li>

        <?php if ($role == 1 || $role == 3): ?>
            <li class="module-manager <?php echo ($page == 'product_manager') ? 'active' : ''; ?>">
                <a href="index.php?page=product_manager"><i class="fas fa-box"></i> Sản phẩm</a>
            </li>
        <?php endif; ?>

        <?php if ($role == 1): ?>
            <li class="module-manager <?php echo ($page == 'user_manager') ? 'active' : ''; ?>">
                <a href="index.php?page=user_manager"><i class="fas fa-users"></i> Tài khoản</a>
            </li>
        <?php endif; ?>

        <?php if ($role == 1 || $role == 3): ?>
            <li class="module-manager <?php echo ($page == 'customer_manager') ? 'active' : ''; ?>">
                <a href="index.php?page=customer_manager"><i class="fas fa-user-friends"></i> Khách hàng</a>
            </li>
        <?php endif; ?>

        <?php if ($role == 1 || $role == 2): ?>
            <li class="module-manager <?php echo ($page == 'import_manager') ? 'active' : ''; ?>">
                <a href="index.php?page=import_manager"><i class="fa-solid fa-file-import"></i></i>Nhập hàng</a>
            </li>
        <?php endif; ?>

        <?php if ($role == 1 || $role == 2): ?>

            <li class="module-manager <?php echo ($page == 'supplier_manager') ? 'active' : ''; ?>">
                <a href="index.php?page=supplier_manager"><i class="fa-solid fa-truck-field"></i></i>Nhà cung cấp</a>
            </li>
        <?php endif; ?>

        <?php if ($role == 1): ?>

            <li class="menu-title">THỐNG KÊ</li>

            <li class="module-manager <?php echo ($page == 'stats_customer') ? 'active' : ''; ?>">
                <a href="index.php?page=stats_customer"><i class="fas fa-chart-bar"></i> Thống kê khách hàng</a>
            </li>
        <?php endif; ?>

    </ul>
</aside>