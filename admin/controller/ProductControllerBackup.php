<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Product.php';

$model_product = new Model_Product($connection);

$action = $_POST['action'] ?? $_GET['action'] ?? null;

$limit = $_SESSION['limit'];
$offset = $_SESSION['offset'];

switch ($action) {
    case 'delete_product':
        $id = $_POST['id'];
        $pagination = $_POST['pagination'] ?? 1;
        $dispatch = $_POST['dispatch'] ?? null;
        if ($dispatch == 'lock') {
            $value = 0; // Ẩn sản phẩm
        } else {
            $value = 1; // Hiện sản phẩm
        }
        // Gọi model để xóa sản phẩm
        $deleted = $model_product->deleteProduct($id, $value);

        header("Location: /DoAn/ShoesStore/admin/view/index.php?page=product_manager&tab=product&pagination=$pagination");
        exit();
    case 'get_product_without_pagination':
        $list = $model_product->getAllProductsWithoutPagination();
        if ($list) {
            $_SESSION['list_product'] = $list;
        } else {
            $_SESSION['list_product'] = [];
        }
        break;
    case 'get_product_id':
        $id = $_SESSION['product_id'] ?? null;
        if ($id) {
            $product = $model_product->getProductById($id);
            if ($product) {
                $_SESSION['product'] = $product;
            } else {
                $_SESSION['message'] = "Không tìm thấy sản phẩm!";
                $_SESSION['message_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "ID sản phẩm không hợp lệ!";
            $_SESSION['message_type'] = "error";
        }
        break;
    default:
        $_SESSION['message'] = "Hành động không hợp lệ!";
        $_SESSION['message_type'] = "error";
}
