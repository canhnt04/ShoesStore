<?php
include_once __DIR__ . '/../includes/alert_message.php';
include __DIR__ . '/../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'account';
?>
<header class="header_account-page">

    <a class="<?php echo ($tab == 'account') ? 'active' : '' ?>" href="index.php?page=customer_manager&tab=account">Thông tin khách hàng</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'orders') ? 'active' : '' ?>" href="index.php?page=customer_manager&tab=orders">Quản lý hóa đơn </a>

</header>

<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'customer_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'account':
                    include __DIR__ . '/../includes/customer-action/account_customer.php';
                    break;
                case 'orders':
                    include __DIR__ . '/../includes/customer-action/order_action.php';
                    break;

                default:
                    include '../includes/customer-action/account_customer.php';
                    break;
            }
        } else {
            include __DIR__ . '/../includes/customer-action/account_customer.php';
        }
        ?>

    </div>