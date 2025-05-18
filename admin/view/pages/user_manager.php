<?php
// session_start(); // Bắt đầu phiên làm việc
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'account';

include __DIR__ . '/../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();

?>
<header class="header_account-page">

    <a class="<?php echo ($tab == 'account') ? 'active' : '' ?>" href="index.php?page=user_manager&tab=account">Tài khoản người dùng</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'role') ? 'active' : '' ?>" href="index.php?page=user_manager&tab=role">Vai trò</a>

</header>

<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'user_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'account':
                    include __DIR__ . '/../includes/account-action/account.php';
                    break;
                case 'role':
                    include __DIR__ . '/../includes/account-action/role.php';
                    break;

                default:
                    include '../includes/account-action/account.php';
                    break;
            }
        } else {
            include __DIR__ . '/../includes/account-action/account.php';
        }
        ?>

    </div>
</div>