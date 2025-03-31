<?php
    // File định tuyến router

    $controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    // Load controller class
    $controllerName = ucfirst($controller) . 'Controller';
    require_once __DIR__ . "/controller/{$controllerName}.php";

    // Khởi tạo controller và gọi phương thức
    $controllerInstance = new $controllerName();
    $controllerInstance->$action($id);
?>