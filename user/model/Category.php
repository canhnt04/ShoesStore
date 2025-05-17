<?php
require_once __DIR__ . "/../../config/init.php";



class Category
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection(); // Gán vào thuộc tính của class
    }

    public function getAll()
    {
        $sql = "SELECT * FROM category";
        $result = $this->con->query($sql);

        $categoryList = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoryList[] = $row;
            }
        }

        return $result;
    }
}
