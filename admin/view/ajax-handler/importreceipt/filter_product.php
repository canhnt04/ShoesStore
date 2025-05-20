<?php
include_once __DIR__ . '/../../../controller/ProductController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
$productController = new ProductController($connection);

// Xác định số sản phẩm trên mỗi trang
$limit = 10;

// Lấy số trang hiện tại từ tham số GET, mặc định là 1
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Tính toán offset
$offset = ($page - 1) * $limit;

// Lấy danh sách sản phẩm
$products = $productController->getAllPaginated($limit, $offset);

// Lấy tổng số sản phẩm để tính tổng số trang
$totalProducts = $productController->countList();
$totalPages = ceil($totalProducts / $limit);
$tbody = '';
foreach ($products as $product) {
    $tbody .= '<tr>';
    $tbody .= '<td>' . htmlspecialchars($product->getId()) . '</td>';
    $tbody .= '<td>' . htmlspecialchars($product->getName()) . '</td>';
    $tbody .= '<td><button class="btn-choose-product" data-id="'
        . htmlspecialchars($product->getId())
        . '">Chọn</button></td>';

    $tbody .= '</tr>';
}

// Tạo HTML cho phân trang
$pagination = '';
for ($i = 1; $i <= $totalPages; $i++) {
    $pagination .= '<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a> ';
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode([
    'success' => true,
    'tbody' => $tbody,
    'pagination' => $pagination
]);
