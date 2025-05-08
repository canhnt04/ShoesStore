<?php
require_once("Database.php");
require_once("Product.php");
require_once("Cart.php");

class Payment
{
    private $con;
    private $productModel;
    private $cartModel;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }

    public function createOrder($userId, $cart, $method, $adress)
    {
        try {
            // Create Order
            $sql = "INSERT INTO orders (user_id, status_id, created_at, updated_at, paymethod)
                    VALUES ($userId, 1, NOW(), NOW(), '$method')";
            $this->con->query($sql);
            $order_id = $this->con->insert_id;

            // Create OrderDetail
            foreach ($cart as $cartItem) {
                $product_id = $cartItem['product_id'];
                $quantity = $cartItem['quantity'];
                $totalPrice = $cartItem['price'] * $quantity;

                $sql2 = "INSERT INTO orderdetail (order_id, product_id, quantity, price, created_at, updated_at)
                         VALUES ($order_id, $product_id, $quantity, $totalPrice, NOW(), NOW())";
                $this->con->query($sql2);
                $this->productModel->updateQuantity($cartItem["detail_id"], -$cartItem["quantity"]);
                $this->cartModel->removeFromCart($product_id, $userId);
            }
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex);
        }
    }
}
