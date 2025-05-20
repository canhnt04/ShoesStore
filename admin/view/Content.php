<div class="main-content">

    <div class="content">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'product_manager':
                    include './pages/product_manager.php';
                    break;
                case 'user_manager':
                    include './pages/user_manager.php';
                    break;
                case 'customer_manager':
                    include './pages/customer_manager.php';
                    break;
                case 'stats_customer':
                    include './pages/stats_customer.php';
                    break;
                case 'import_manager':
                    include './pages/import_manager.php';
                    break;

                case 'supplier_manager':
                    include './pages/supplier_manager.php';
                    break;
                default:
                    include './pages/home.php';
                    break;
            }
        } else {
            include './pages/home.php';
        }

        ?>
    </div>

</div>