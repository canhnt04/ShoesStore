<?php
session_start(); // Bắt đầu phiên làm việc
include_once __DIR__ . '/../includes/alert_message.php';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'product';
?>


<header class="header_account-page">

    <a class="<?php echo ($tab == 'product') ? 'active' : '' ?>" href="index.php?page=product_manager&tab=product">Quản lý sản phẩm</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'detail') ? 'active' : '' ?>" href="index.php?page=product_manager&tab=detail">Quản lý chi tiết sản phẩm</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'category') ? 'active' : '' ?>" href="index.php?page=product_manager&tab=category">Quản lý danh mục</a>

</header>


<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'product_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'product':
                    include __DIR__ . '/../includes/product-action/product.php';
                    break;
                case 'detail':
                    include __DIR__ . '/../includes/product-action/detail.php';
                    break;
                case 'category':
                    include __DIR__ . '/../includes/product-action/category.php';
                    break;

                default:
                    include __DIR__ . '/../includes/product-action/product.php';
                    break;
            }
        } else {
            include __DIR__ . '/../includes/product-action/product.php';
        }
        ?>

    </div>