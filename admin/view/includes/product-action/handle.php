<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require_once __DIR__ . '/../../../controller/ProductController.php';
    require_once __DIR__ . '/../../../controller/ProductDetailController.php';
    // Giả định $connection đã có hoặc bạn cần include kết nối CSDL
    $controllerProduct = new ProductController($connection);
    $controllerProductDetail = new ProductDetailController($connection);

    switch ($_POST['action']) {
        // product
        case 'create_product':
            $res = $controllerProduct->create($_POST);
            header("Location: index.php?page=product_manager&tab=product&pagination=" . ($_POST['pagination'] ?? 1));
            exit;
        case 'update_product':
            $controllerProduct->update($_POST);

            header("Location: index.php?page=product_manager&tab=product&pagination=" . ($_POST['pagination'] ?? 1));
            exit;
        case 'delete_product':
            $controllerProduct->toggleStatus($_POST['id'], $_POST['dispatch']);
            header("Location: index.php?page=product_manager&tab=product&pagination=" . ($_POST['pagination'] ?? 1));
            exit;
            // detail
        case 'create_detail_product':
            $controllerProductDetail->createProductDetail($_POST);
            header("Location: index.php?page=product_manager&tab=detail&pagination=" . ($_POST['pagination'] ?? 1));
            exit;
        case 'update_detail_product':
            $controllerProductDetail->updateProductDetail($_POST);
            header("Location: index.php?page=product_manager&tab=detail&pagination=" . ($_POST['pagination'] ?? 1));
            exit;
        case 'delete_detail_product':
            $controllerProductDetail->toggleDetailProduct($_POST['id'], $_POST['dispatch']);
            header("Location: index.php?page=product_manager&tab=detail&pagination=" . ($_POST['pagination'] ?? 1));
            exit;
    }
}
