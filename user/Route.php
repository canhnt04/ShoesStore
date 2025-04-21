<?php
    // File định tuyến router
    session_start();

    $_SESSION['username'] = "HuyNek";
    $_SESSION['password'] = "alibaba";
    $_SESSION['userId'] = 1;
    // Lấy tên controller
    $controller = isset($_GET['page']) ? $_GET['page'] : 'Home';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    
    // Tham số động
    // $pageNumber = isset($_GET['pageNumber']) ? $_GET['pageNumber'] : 1;
    // $id = isset($_GET['id']) ? $_GET['id'] : null;

    // Tạo mảng để lấy tham số
    $params= [];
    foreach ($_REQUEST as $key => $value) {
        if ($key !== 'page' && $key !== 'action') {
            $params[$key] = $value;
        }
    }

    // Load controller class
    $controllerName = ucfirst($controller) . 'Controller';
    require_once __DIR__ . "/controller/{$controllerName}.php";

    // Khởi tạo controller và gọi phương thức tại file này
    $controllerInstance = new $controllerName();
    $controllerInstance->$action($params);
?>