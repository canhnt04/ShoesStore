<?php
session_start();
include_once __DIR__ . '/../../../config/database/ConnectDB.php';
include_once __DIR__ . '/../../model/Model/Model_User.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$role_id = 1;
$status = 1;
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

$model_user = new Model_User($connection);
$user = $model_user->createUser($username, $password, $email, 1, 1, $created_at, $updated_at);

// Kiểm tra kết quả và lưu vào session
if ($user) {
    $_SESSION['message'] = "Tạo mới người dùng thành công!";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Tạo người dùng thất bại!";
    $_SESSION['message_type'] = "error";
}

// Quay lại trang form
header("Location: /DoAn/ShoesStore/admin/view/index.php?page=account_manager");
exit();
