<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Category.php';

$model_category = new Model_Category($connection);

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'get_all_categorys':
        $categorys = $model_category->getAllCategories();
        $_SESSION['categorys'] = $categorys;
        break;
    default:
        $_SESSION['message'] = "Hành động không hợp lệ!";
        $_SESSION['message_type'] = "error";
}
