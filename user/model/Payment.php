<?php
require_once("Database.php");

class Payment
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function createOrder($userId, $cart, $method, $adress)
    {
        try {
            // Create Order
            $sql = "INSERT INTO orders (user_id, status_id, paymethod)
                    VALUES ($userId, 1, '$method')";
            $this->con->query($sql);
            $order_id = $this->con->insert_id;

            // Create OrderDetail
            foreach ($cart as $cartItem) {
                $product_id = $cartItem['product_id'];
                $quantity = $cartItem['quantity'];
                $totalPrice = $cartItem['price'] * $quantity;

                $sql2 = "INSERT INTO orderdetail (order_id, product_id, quantity, price)
                         VALUES ($order_id, $product_id, $quantity, $totalPrice)";
                $this->con->query($sql2);
            }
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex);
        }
    }
}
