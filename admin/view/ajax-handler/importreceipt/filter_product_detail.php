<?php
include_once __DIR__ . '/../../../controller/ProductDetailController.php';
include_once __DIR__ . '/../../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();

$productDetailController = new ProductDetailController($connection);
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    $details = $productDetailController->getAllDetailsByProductId($product_id);

    $tbody = '';
    foreach ($details as $detail) {
        $tbody .= '<tr>';
        $tbody .= '<td>' . htmlspecialchars($detail->getId()) . '</td>';
        $tbody .= '<td>' . htmlspecialchars($detail->getSize()) . '</td>';
        $tbody .= '<td>' . htmlspecialchars($detail->getColor()) . '</td>';
        $tbody .= '<td>' . htmlspecialchars($detail->getMaterial()) . '</td>';
        $tbody .= '<td><button class="add-to-import" data-id="' . htmlspecialchars($detail->getId()) . '">Chọn</button></td>';
        $tbody .= '</tr>';
    }

    echo json_encode([
        'success' => true,
        'tbody' => $tbody
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
}
