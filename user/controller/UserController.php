<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../../config/init.php";

class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function profile()
    {
        try {
            $userId = $_SESSION['userId'];
            $profile = $this->userModel->getUserByid($userId);
            $this->render("Profile.php", ["user" => $profile]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage()
            ]);
        }
    }
}
