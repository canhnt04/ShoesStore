<?php
include_once __DIR__ . '/../../../controller/SupplierController.php';

// Assuming you have a $connection object available or require a database connection file
require_once __DIR__ . '/../../../../config/database/ConnectDB.php'; // adjust path as needed
$supplierController = new SupplierController($connection);
$suppliers = $supplierController->getAllSuppliers();

$supplierOptions = [];

foreach ($suppliers as $supplier) {
    $supplierOptions[] = [
        'id' => $supplier->getId(),
        'name' => $supplier->getName()
    ];
}

echo json_encode([
    'success' => true,
    'suppliers' => $supplierOptions
]);
