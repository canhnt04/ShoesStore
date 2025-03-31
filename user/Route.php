<?php
    // File định tuyến router

    // Lấy tên controller
    $controller = isset($_GET['page']) ? $_GET['page'] : 'Home';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    // Load controller class
    $controllerName = ucfirst($controller) . 'Controller';
    require_once __DIR__ . "/controller/{$controllerName}.php";

    // Khởi tạo controller và gọi phương thức
    $controllerInstance = new $controllerName();
    $controllerInstance->$action($id);
?>