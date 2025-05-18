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
    public function getListSuppliers(array $filters = [], int $perPage = 5, int $page = 1): array
    {
        $result = $this->model_supplier->getListSuppliers($filters, $perPage, $page);

        return [
            'suppliers' => $result['suppliers'] ?? [],
            'totalPages' => $result['totalPages'] ?? 1,
        ];
    }
}
