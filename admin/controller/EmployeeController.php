<?php
include_once __DIR__ . '/../model/Model/Model_Employee.php';
include_once __DIR__ . '/../../config/init.php';

class EmployeeController
{
    private $employeeModel;

    public function __construct()
    {
        global $connection; // Sử dụng biến toàn cục
        $this->employeeModel = new Model_Employee($connection);
    }

    public function countEmployees()
    {
        return $this->employeeModel->countEmployees();
    }

    public function listEmployees($limit, $offset)
    {
        return $this->employeeModel->getAllEmployees($limit, $offset);
    }
}
