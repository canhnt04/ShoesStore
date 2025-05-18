<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Customer.php';


class CustomerController
{
    private $customerModel;

    public function __construct()
    {
        global $connection; // Sử dụng biến toàn cục
        $this->customerModel = new Model_Customer($connection);
    }

    public function listCustomers()
    {
        // Lấy danh sách người dùng từ model
        return  $this->customerModel->getAllCustomers();
        // include __DIR__ . '/../view/includes/account-action/account_action.php';
    }

    public function getTopCustomers($startDate, $endDate, $sortOrder = 'DESC')
    {
        return $this->customerModel->getTopCustomers( $startDate, $endDate, $sortOrder);
    }
}
