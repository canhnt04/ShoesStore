<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Cart.php";

class CartController extends BaseController
{
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    public function showCart($params)
    {
        echo 1;
        if (!isset($_SESSION["userId"])) {
            http_response_code(401);
            echo json_encode([
                "status" => 401,
                "message" => "Bạn chưa đăng nhập!",
            ]);
            return;
        }
        $userId = $_SESSION["userId"];
        try {
            $cart = $this->cartModel->getCartByUserId($userId);
            $this->render("Cart.php", ["cart" => $cart]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage()
            ]);
        }
    }

    public function buyProduct($params)
    {
        // Phần tử thuộc $GET trong params: page=Product&action=buyProduct
        // $POST trong params: prdetail_id, pr_id, product-size, ...
        if (!isset($_SESSION["userId"])) {
            http_response_code(401);
            echo json_encode([
                "status" => 401,
                "message" => "Bạn chưa đăng nhập!",
            ]);
            return;
        }
        $userId = $_SESSION["userId"];
        $productDetails = new stdClass();
        $productDetails->id = $params['prdetail_id'];
        $productDetails->product_id = $params['pr_id'];
        $productDetails->quantity = $params['product-quanity'];
        $model = $this->productModel->getProductDetailByID($productDetails->product_id, $productDetails->id);
        $productDetails->price = $model->price;

        try {
            $check = $this->cartModel->addToCart($productDetails, $userId);

            $cart = $this->cartModel->getCartByUserId($userId);

            $this->render("Cart.php", [
                "cart" => $cart
            ]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage(),
            ]);
        }
    }

    public function addToCart($params)
    {
        if (!isset($_SESSION["userId"])) {
            http_response_code(401);
            echo json_encode([
                "status" => 401,
                "message" => "Bạn chưa đăng nhập!",
            ]);
            return;
        }
        $userId = $_SESSION["userId"];
        if (isset($params['prdetail_id'])) { // If này quên nó làm gì rồi :V, mà xóa thì không chạy được.
            $productDetails = new stdClass();
            $productDetails->id = $params['prdetail_id'];
            $productDetails->product_id = $params['pr_id'];
            $productDetails->quantity = $params['product_quantity'];
            $model = $this->productModel->getProductDetailByID($productDetails->product_id, $productDetails->id);
            $productDetails->price = $model->price;

            try {
                $check = $this->cartModel->addToCart($productDetails, $userId);

                echo json_encode([
                    "status" => 200,
                    "message" => "Đã cập nhật giỏ hàng!",
                ]);
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode([
                    "status" => 500,
                    "message" => $ex->getMessage(),
                ]);
            }
        }
    }

    public function updateQuantity($params)
    {
        if (!isset($_SESSION["userId"])) {
            http_response_code(401);
            echo json_encode([
                "status" => 401,
                "message" => "Bạn chưa đăng nhập!",
            ]);
            return;
        }
        $userId = $_SESSION["userId"];
        $cartDetailId = $params["cartDetail_id"];
        $quantity = $params["quantity"];

        try {
            $this->cartModel->updateQuantity($cartDetailId, $quantity);
            $cart = $this->cartModel->getCartByUserId($userId);

            $this->render("Cart.php", ["cart" => $cart]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage(),
            ]);
        }
    }

    public function removeFromCartDetail($params)
    {
        $userId = $_SESSION["userId"];
        $cartDetailId = $params["cartDetail_id"];

        try {
            $this->cartModel->removeFromCartDetail($cartDetailId);
            $cart = $this->cartModel->getCartByUserId($userId);

            $this->render("Cart.php", ["cart" => $cart]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode([
                "status" => 500,
                "message" => $ex->getMessage(),
            ]);
        }
    }
}
