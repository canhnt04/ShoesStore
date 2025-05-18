<?php
include_once __DIR__ . '/../../config/init.php';
include_once __DIR__ . '/../model/Model/Model_Supplier.php';

class SupplierController
{
    private $model_supplier;

    public function __construct($connection)
    {
        $this->model_supplier = new Model_Supplier($connection);
    }

    public function getAllSuppliers()
    {
        return  $this->model_supplier->getAllSuppliers();
    }
}
