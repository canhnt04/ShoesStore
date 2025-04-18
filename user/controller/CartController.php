<?php
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Cart.php";


class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    public function showCart($params) {
        $userId = $_SESSION["userId"];
        $cart = $this->cartModel->getCartByUserId($userId);
        include(__DIR__ . "../../resource/shared/Header.php" );
        include(__DIR__ . "../../view/Cart.php");
        include(__DIR__ . "../../resource/shared/Footer.php" );
    }
}
?>