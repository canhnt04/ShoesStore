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


    <!-- <form action="/DoAn/ShoesStore/admin/controller/User_Controller/create_user.php" method="POST">
        <input type="hidden" name="action" value="create_user">
        <div class="form-group">
            <label for="username">Name:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="role_id">Role ID:</label>
            <input type="number" class="form-control" id="role_id" name="role_id" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form> -->