<div class="main-content">

    <div class="content">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'account_manager':
                    include './pages/account_manager.php';
                    break;
                case 'product_manager':
                    include './pages/product_manager.php';
                    break;
                case 'staff_manager':
                    include './pages/staff_manager.php';
                    break;
                case 'customer_manager':
                    include './pages/customer_manager.php';
                    break;
                case 'stats_customer':
                    include './pages/stats_customer.php';
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