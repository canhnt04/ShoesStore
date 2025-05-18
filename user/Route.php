<?php
require_once __DIR__ . "/../config/init.php";

// Điều hướng mặc định nếu không có page và action
if (!isset($_GET['page']) && !isset($_GET['action'])) {
    header('Location: /ShoesStore/public/index.php?page=Product&action=showList&pageNumber=1');
    exit();
}

// Lấy tên controller và action
$controller = $_GET['page'] ?? 'Home';
$action = $_GET['action'] ?? 'index';

// Gom các tham số phụ
$params = [];
foreach ($_REQUEST as $key => $value) {
    if ($key !== 'page' && $key !== 'action') {
        $params[$key] = $value;
    }
}

// Định nghĩa tên class controller
$controllerName = ucfirst($controller) . 'Controller';

// Kiểm tra xem file controller có tồn tại không
$controllerPath = __DIR__ . "/controller/{$controllerName}.php";
if (!file_exists($controllerPath)) {
    header('Location: /ShoesStore/public/404.php');
    exit();
}
require_once $controllerPath;

// Tạo instance
$controllerInstance = new $controllerName();

// Kiểm tra phương thức tồn tại trước khi gọi
if (!method_exists($controllerInstance, $action)) {
    header('Location: /ShoesStore/public/404.php');
    exit();
}

// Gọi action
$controllerInstance->$action($params);
