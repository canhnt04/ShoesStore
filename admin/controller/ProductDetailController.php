<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_ProductDetail.php';

$model_product_detail = new Model_ProductDetail($connection);

$action = $_POST['action'] ?? $_GET['action'] ?? null;

$limit = $_SESSION['limit'];
$offset = $_SESSION['offset'];

switch ($action) {
    case 'count_list':
        $count = $model_product_detail->countProductDetails();
        if ($count) {
            $_SESSION['count'] = $count;
        } else {
            $count = 0;
        }
        break;
    case 'render_view':
        $list = $model_product_detail->getAllProductDetails($limit, $offset);
        if ($list) {
            $_SESSION['detail_view'] = $list;
        } else {
            $_SESSION['detail_view'] = [];
        }
        break;
}
