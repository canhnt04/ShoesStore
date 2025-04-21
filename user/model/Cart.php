<?php
require_once("Database.php");

class Cart
{
    private $con;

    public function __construct() {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function addToCart($productDetails, $userId, $cartId) {
        try {
            $sqlCartDetails = 
                "INSERT INTO cartdetail (cart_id, product_id, quantity, price, status)
                 VALUES ($cartId, {$productDetails->product_id}, {$productDetails->quantity}, {$productDetails->price}, 1)";
            
            $result = $this->con->query($sqlCartDetails);
    
            if (!$result) {
                throw new Exception("MySQL Error: " . $this->con->error);
            }
    
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "Lỗi thêm giỏ hàng: " . $e->getMessage();
            return false;
        }
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