<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Product.php';

$model_product = new Model_Product($connection);

$action = $_POST['action'] ?? $_GET['action'] ?? null;

$limit = $_SESSION['limit'];
$offset = $_SESSION['offset'];

switch ($action) {
    case 'count_list':
        $count = $model_product->countProducts();
        if ($count) {
            $_SESSION['count'] = $count;
        } else {
            $count = 0;
        }
        break;
    case 'render_view':
        $list = $model_product->getAllProducts($limit, $offset);
        if ($list) {
            $_SESSION['product_view'] = $list;
        } else {
            $_SESSION['product_view'] = [];
        }
        break;
    case 'create_product':
        $name = $_POST['name'];
        $supplier_id = (int)$_POST['supplier_id'];
        $category_id = (int)$_POST['category_id'];
        $status = $_POST['status'];
        $pagination = $_POST['pagination'] ?? 1;

        // Xử lý hình ảnh upload
        $thumbnail = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $thumbnail = basename($_FILES['thumbnail']['name']);
            $uploadPath = $uploadDir . $thumbnail;

            // Tạo thư mục nếu chưa có
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath);
        }

        // Gọi model để lưu sản phẩm
        $product = $model_product->createProduct($name, $thumbnail, $supplier_id, $category_id, $status);

        header("Location: /DoAn/ShoesStore/admin/view/index.php?page=product_manager&tab=product&pagination=$pagination");
        exit();
        break;
    case 'update_product':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $pagination = $_POST['pagination'] ?? 1;
        $oldThumbnail = $_POST['old_thumbnail'] ?? '';
        $thumbnail = $oldThumbnail; // mặc định giữ ảnh cũ

        // Kiểm tra nếu người dùng upload ảnh mới
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $newThumbnail = uniqid('product_', true) . '.' . $ext;
            $uploadPath = $uploadDir . $newThumbnail;

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                $thumbnail = $newThumbnail;

                if ($oldThumbnail && $oldThumbnail !== 'no_image.png') {
                    $oldPath = $uploadDir . $oldThumbnail;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            } else {
                error_log("Không thể upload ảnh vào: $uploadPath");
            }
        }

        // Gọi model để cập nhật
        $updated = $model_product->updateProduct($id, $name, $thumbnail);

        header("Location: /DoAn/ShoesStore/admin/view/index.php?page=product_manager&tab=product&pagination=$pagination");
        exit();
        break;
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
        break;
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
