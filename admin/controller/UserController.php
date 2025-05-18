<?php
include_once __DIR__ . '/../../config/init.php';
include_once __DIR__ . '/../model/Model/Model_User.php';
class UserController
{
    private $userModel;

    public function __construct()
    {
        global $connection; // Sử dụng biến toàn cục
        $this->userModel = new Model_User($connection);
    }

    public function countUsers()
    {
        return $this->userModel->countUsers();
    }

    public function listUsers($limit, $offset)
    {
        return $this->userModel->getAllUsers($limit, $offset);
    }

    // public function listUsers()
    // {
    //     // Lấy danh sách người dùng từ model
    //     return  $this->userModel->getAllUsers();
    //     // include __DIR__ . '/../view/includes/account-action/account_action.php';
    // }
}
