<?php
require_once __DIR__ . "/../config/init.php";

// Lấy tên controller
$controller = $_GET['page'] ?? 'Home';
$action = $_GET['action'] ?? 'index';

// Tạo mảng để lấy tham số
$params = [];
foreach ($_REQUEST as $key => $value) {
    if ($key !== 'page' && $key !== 'action') {
        $params[$key] = $value;
    }
}

// Load controller class
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile =  __DIR__ . "/controller/{$controllerName}.php";

if (!file_exists($controllerFile)) {
    header('Location: /ShoesStore/public/404.php');
    exit();
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    header('Location: /ShoesStore/public/404.php');
    exit();
}

// Khởi tạo controller và gọi phương thức tại file này
$controllerInstance = new $controllerName();

if (!method_exists($controllerInstance, $action)) {
    header('Location: /ShoesStore/public/404.php');
    exit();
}

$controllerInstance->$action($params);
