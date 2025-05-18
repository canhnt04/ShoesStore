<?php

include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();

// Trả về JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accountId'])) {
    require_once __DIR__ . '/../../../controller/UserController.php';
    $accountId = (int)$_POST['accountId'];
    $status = (int)$_POST['status'];

    $userController = new UserController($connection);

    $newStatus = ($status == 1) ? 0 : 1;

    $success = $userController->toggleUserStatus($accountId, $newStatus);

    if ($success) {
        echo json_encode([
            'status' => 'success',
            'message' => ($newStatus == 0 ? 'Đã khóa tài khoản thành công.' : 'Đã mở khóa tài khoản thành công.')
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Cập nhật trạng thái thất bại.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    ]);
}
