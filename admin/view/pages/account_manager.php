<?php
session_start(); // Bắt đầu phiên làm việc
include_once __DIR__ . '/../includes/alert_message.php';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'account';
?>
<header class="header_account-page">

    <a class="<?php echo ($tab == 'account') ? 'active' : '' ?>" href="index.php?page=account_manager&tab=account">Quản lý tài khoản</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'permision') ? 'active' : '' ?>" href="index.php?page=account_manager&tab=permision">Quản lý vai trò</a>

</header>

<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'account_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'account':
                    include __DIR__ . '/../includes/account-action/account_action.php';
                    break;
                case 'permision':
                    include __DIR__ . '/../includes/account-action/permision_action.php';
                    break;

                default:
                    include '../includes/account-action/account_action.php';
                    break;
            }
        } else {
            include __DIR__ . '/../includes/account-action/account_action.php';
        }
        ?>

    </div>