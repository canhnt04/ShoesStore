<?php 
require_once(__DIR__ . "../BaseController.php");
require_once __DIR__ . "/../model/Product.php";

class PaymentController extends BaseController
{
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function checkout($params) {
        $selectedCartItem = json_decode($params["products"], true);
        if (!isset($_SESSION["userId"])) {
            http_response_code(401);
            echo json_encode([
                "status" => 401,
                "message" => "Bạn chưa đăng nhập!",
            ]);
            return;
        }
        $this->render("Checkout.php", ["cart" => $selectedCartItem]);
    }
}
?>