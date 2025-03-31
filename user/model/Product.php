<?php
require_once("Database.php");

class Product
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection(); // Gán vào thuộc tính của class
    }

    public function getAll() {
        $sql = "SELECT * FROM product";
        $result = $this->con->query($sql);

        $productList = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productList[] = $row;
            }
        }
        return $productList;
    }
}
?>