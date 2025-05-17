<?php
require_once(__DIR__ . "/BaseController.php");

class HomeController extends BaseController
{

    public function home($id = null)
    {
        echo "Trang Home Page";
        if ($id) {
            echo " ID: " . $id;
        }
    }

    public function index()
    {
        // Gọi view Home.php
        $this->render("Home.php");
    }

    public function contact()
    {
        $this->render("Footer.php");
    }
}
