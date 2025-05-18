<?php
require_once __DIR__ . "/../config/init.php";

$role = $_SESSION['role'] ?? null;

session_unset();
session_destroy();

if ($role == '4') {
    header('Location: /ShoesStore/public/index.php?page=Product&action=showList&pageNumber=1');
    exit();
} else {
    header('Location: /ShoesStore/public/index.php?page=Auth&action=auth');
    exit();
}
