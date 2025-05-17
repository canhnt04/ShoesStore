<?php
require_once __DIR__ . '/../../controller/ProductController.php';
require_once __DIR__ . '/../../controller/ProductDetailController.php';
require_once __DIR__ . '/../../controller/CategoryController.php';
require_once __DIR__ . '/../../../config/database/ConnectDB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST["productId"];

    $controllerProduct = new ProductController($connection);
    $controllerDetail = new ProductDetailController($connection);
    $categoryController = new CategoryController($connection);

    $product = $controllerProduct->getProductById($productId);
    $details = $controllerDetail->getAllDetailsByProductId($productId);

    $categorys = $categoryController->getAllCategories();
    $categoryMap = [];
    foreach ($categorys as $category) {
        $categoryMap[$category->getId()] = [
            'name' => $category->getName()
        ];
    }
};
function formatPriceVND($price)
{
    // Ép kiểu về float
    $price = (float) $price;

    // Định dạng: không hiển thị số lẻ, dùng dấu . ngăn cách hàng nghìn
    $formattedPrice = number_format($price, 0, ',', '.');

    // Thêm đơn vị VNĐ
    return $formattedPrice . ' ₫';
}
?>

<div style="display: flex; justify-content: center;">
    <img src="/ShoesStore/admin/uploads/<?= htmlspecialchars($product->getThumbnail()) ?>"
        alt="Hình ảnh sản phẩm"
        width="200"
        onerror="this.onerror=null;this.src='/DoAn/ShoesStore/public/assets/images/no_image.png';">
    <div style="text-align: start; margin: 0 20px;">
        <h2><?= htmlspecialchars($product->getName()) ?></h2>
        <p>Thương hiệu: <?= htmlspecialchars($product->getBrand()) ?></p>
        <p>Danh mục: <?= htmlspecialchars($categoryMap[$product->getCategoryId()]['name']) ?></p>
    </div>
</div>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>ID chi tiết</th>
            <th>Số lượng</th>
            <th>Size</th>
            <th>Màu sắc</th>
            <th>Chất liệu</th>
            <th>Giá</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($details)): ?>
            <?php foreach ($details as $detail): ?>
                <tr>
                    <td><?= htmlspecialchars($detail->getId()) ?></td>
                    <td><?= htmlspecialchars($detail->getQuantity()) ?></td>
                    <td><?= htmlspecialchars($detail->getSize()) ?></td>
                    <td><?= htmlspecialchars($detail->getColor()) ?></td>
                    <td><?= htmlspecialchars($detail->getMaterial()) ?></td>
                    <td><?= formatPriceVND($detail->getPrice()) ?></td>
                    <td> <?php if ($detail->getStatus() == 0): ?>
                            <span style="background-color: red; color: white; font-size: 14px; padding: 2px 4px; border-radius: 4px;">Đã ẩn</span>
                        <?php else: ?>
                            <span style="background-color: green; color: white; font-size: 14px; padding: 2px 4px; border-radius: 4px;">Đang bán</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align: center;">Sản phẩm chưa có chi tiết</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>