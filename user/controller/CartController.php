<?php
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Cart.php";
require_once __DIR__ . "/BaseController.php";

class CartController extends BaseController{
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    public function showCart($params) {
        $userId = $_SESSION["userId"];
        $cart = $this->cartModel->getCartByUserId($userId);
        $this->render("Cart.php", ["cart" => $cart]);
    }
}
?>