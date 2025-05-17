<?php
require_once __DIR__ . "/../../../config/init.php";

$role = $_SESSION['role'] ?? null;

unset($_SESSION['userId']);
unset($_SESSION['role']);

if ($role == '4') {
    header('Location: /ShoesStore/public/index.php?page=Product&action=showList&pageNumber=1');
    exit();
} else {
    header('Location: ./Auth.php');
    exit();
}
