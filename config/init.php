<?php
session_start();
// if (
//     !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
//     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
// ) {
//     header("Location: ../index.php");
//     exit;
// }

// Autoload class
// spl_autoload_register(function ($className) {
//     $paths = [
//         __DIR__ . "/user/controller/{$className}/php",
//         __DIR__ . "/user/model/$className}php",
//     ];
//     foreach ($paths as $path) {
//         if (file_exists($path)) {
//             require_once $path;
//             return;
//         }
//     }
// });

// Connect Database
class Database
{
    private $conn;
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "shoesstore";

    public function __construct()
    {
        $this->connectDB();
    }

    private function connectDB()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
