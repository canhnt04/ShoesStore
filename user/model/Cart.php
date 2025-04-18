<?php
require_once("Database.php");

class Cart
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection(); // Gán vào thuộc tính của class
    }

    public function addToCart($productDetails, $userId) {
        $sqlCart = "INSERT INTO cart (user_id, total_price, status)
                VALUES ($userId, 0, 1)";
        $cartId = $this->con->insert_id;
        $sqlDetail = "INSERT INTO cartdetail (cart_id, product_id, quantity, price)
            VALUES ($cartId, $productDetails->product_id, $productDetails->quantity, $productDetails->price)";
        
        $result = $this->con->query($sqlCart);


    }

    public function getCartByUserId($userId) {
        $sql = "SELECT * FROM cart WHERE user_id = $userId";
        $result = $this->con->query($sql);
    
        $cart = null;
        if ($result->num_rows > 0) {
            $cart = $result->fetch_object();
    
            $sqlDetails = "SELECT cd.*, pd.*
                           FROM cartdetail cd
                           JOIN product pd ON pd.id = cd.product_id
                           WHERE cd.cart_id = $cart->id AND cd.status = 1";
    
            $resultDetails = $this->con->query($sqlDetails);
    
            $cart->cartDetailList = [];
            if ($resultDetails->num_rows > 0) {
                while ($row = $resultDetails->fetch_object()) {
                    $cart->cartDetailList[] = $row;
                }
            }
        }
    
        return $cart;
    }
    
}
?>