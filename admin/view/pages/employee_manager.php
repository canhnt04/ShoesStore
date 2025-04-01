<?php
// session_start(); // Bắt đầu phiên làm việc
include_once __DIR__ . '/../includes/alert_message.php';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'account';
?>
<header class="header_account-page">

    <a class="<?php echo ($tab == 'account') ? 'active' : '' ?>" href="index.php?page=employee_manager&tab=account">Tài khoản nhân viên</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'detail') ? 'active' : '' ?>" href="index.php?page=employee_manager&tab=detail">Thông tin nhân viên</a>

</header>

<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'employee_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'account':
                    include __DIR__ . '/../includes/account-action/account_action.php';
                    break;
                case 'detail':
                    include __DIR__ . '/../includes/account-action/detail_action.php';
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


    <!--  -->