<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Supplier.php';


$limit = $_SESSION['limit'];
$offset = $_SESSION['offset'];

$model_supplier = new Model_Supplier($connection);

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'get_all_suppliers':
        $suppliers = $model_supplier->getAllSuppliers();
        $_SESSION['suppliers'] = $suppliers;
        break;

    default:
        $_SESSION['message'] = "Hành động không hợp lệ!";
        $_SESSION['message_type'] = "error";
}
