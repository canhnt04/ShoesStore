<?php

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "shoesstore";

// Kết nối đến database
$connection = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// // Kiểm tra kết nối
// if (!$connection) {
//     die("Connection failed: " . mysqli_connect_error());
// } else {
//     // echo "Connected successfully";
// }
