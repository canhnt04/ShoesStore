<?php
require_once(__DIR__ . "../BaseController.php");
require_once __DIR__ . "/../model/Payment.php";

class PaymentController extends BaseController
{
    private $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new Payment();
    }

    public function checkout($params)
    {
        if (!isset($_SESSION["userId"])) {
            http_response_code(401);
            echo json_encode([
                "status" => 401,
                "message" => "Bạn chưa đăng nhập!",
            ]);
            return;
        }

        if (isset($params["products"])) {
            $selectedCartItem = json_decode($params["products"], true);

            if (isset($_SESSION["cartSession"])) {
                $_SESSION["cartSession"] = [];
            }
            foreach ($selectedCartItem as $cartItem) {
                $_SESSION["cartSession"][] = $cartItem;
            }
        }

        $this->render("Checkout.php");
    }

    public function placeorder($params) {
        $userId = $_SESSION['userId'];
        $paymentMethod = $params["paymentMethod"];
        $paymentAdress = $params["address"];
        $cart = $_SESSION["cartSession"];

        try{
            $this->paymentModel->createOrder($userId,  $cart, $paymentMethod, $paymentAdress);
            $this->render("Home.php");
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage(),
            ]);
            return;
        }
    }
}
