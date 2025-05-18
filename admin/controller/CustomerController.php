<?php
include_once __DIR__ . '/../model/Model/Model_Customer.php';
include_once __DIR__ . '/../../config/init.php';


class CustomerController
{
    private $customerModel;

    public function __construct($connection)
    {
        
        $this->customerModel = new Model_Customer(connection: $connection);
    }

    public function listCustomers()
    {
        return  $this->customerModel->getAllCustomers();
      ;
    }

    public function getTopCustomers($startDate, $endDate, $sortOrder = 'DESC')
    {
        return $this->customerModel->getTopCustomers( $startDate, $endDate, $sortOrder);
    }
}
