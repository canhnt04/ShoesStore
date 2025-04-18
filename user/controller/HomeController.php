<?php
    class HomeController {

        public function home($id = null) {
            echo "Trang Home Page";
            if ($id) {
                echo " ID: " . $id;
            }
        }

        public function index() {
            include (__DIR__ . "../../resource/shared/Header.php");
            echo "Trang Home Page </br>";
            echo "<a href='Route.php?page=Product&action=showList&pageNumber=1'/> Danh sách sản phẩm";
        }
    }
?>