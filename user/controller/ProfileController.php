<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../model/Customer.php";
require_once __DIR__ . "/../../config/init.php";

class ProfileController extends BaseController
{
    private $userModel;
    private $customerModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->customerModel = new Customer();
    }

    public function profile()
    {
        try {
            $userId = $_SESSION["userId"];
            $user = $this->userModel->getUserByid($userId);
            $this->render("Profile.php", ["user" => $user]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage()
            ]);
        }
    }

    public function updateInfo()
    {
        try {
            $userId = $_SESSION["userId"];
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $result = $this->customerModel->updateUserInfo($userId, $fullname, $phone, $address);
            if ($result) {
                $user = $this->userModel->getUserByid($userId);
                $this->render("Profile.php", ["user" => $user]);
            }
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage()
            ]);
        }
    }
}
