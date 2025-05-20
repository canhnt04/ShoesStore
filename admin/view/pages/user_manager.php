<?php
include __DIR__ . '/../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'account_employee';


?>
<header class="header_account-page">

    <a class="<?php echo ($tab == 'account_employee') ? 'active' : '' ?>" href="index.php?page=user_manager&tab=account_employee">Tài khoản nhân viên</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'account_customer') ? 'active' : '' ?>" href="index.php?page=user_manager&tab=account_customer">Tài khoản khách hàng</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'role') ? 'active' : '' ?>" href="index.php?page=user_manager&tab=role">Vai trò</a>

</header>

<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'user_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'account_employee':
                    include __DIR__ . '/../includes/account-action/account_employee.php';
                    break;
                case 'account_customer':
                    include __DIR__ . '/../includes/account-action/account_customer.php';
                    break;
                case 'role':
                    include __DIR__ . '/../includes/account-action/role.php';
                    break;

                default:
                    include __DIR__ .  '/../includes/account-action/account_employee.php';
                    break;
            }
        } else {
            include __DIR__ . '/../includes/account-action/account_employee.php';
        }
        ?>

    </div>
</div>