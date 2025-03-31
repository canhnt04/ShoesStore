<?php
    class HomeController {

        public function home($id = null) {
            echo "Trang Home Page";
            if ($id) {
                echo " ID: " . $id;
            }
        }

        public function index() {
            echo "Trang Home Page </br>";
            echo "<a href='Route.php?page=Product&action=showList'/> Danh sách sản phẩm";
        }
    }
?>