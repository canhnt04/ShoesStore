<?php
require_once("Database.php");

class Cart
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    private function checkQuantity($detailId, $quantity)
    {
        $sql =  "SELECT pd.id
                FROM productdetail pd
                WHERE pd.id = $detailId AND $quantity <= quantity";

        $check = $this->con->query($sql);

        return $check->num_rows > 0;
    }

    public function addToCart($productDetails, $userId)
    {
        if (!$this->checkQuantity($productDetails->id, $productDetails->quantity)) {
            throw new Exception("Sản phẩm bạn mua không đủ số lượng!");
        }
        try {
            $cartId = $this->getCartByUserId($userId)->id;

            $sqlInsertCartDetails =
                "INSERT INTO cartdetail (cart_id, product_id, quantity, price, created_at, updated_at, product_detail_id, status)
                 VALUES ($cartId, $productDetails->product_id, $productDetails->quantity, $productDetails->price, NOW(), NOW(), $productDetails->id, 1)";
            $this->con->query($sqlInsertCartDetails);
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            throw new Exception("Thêm giỏ hàng thất bại! Vui lòng thử lại sau. " . $ex);
        }
    }

    public function getCartByUserId($userId)
    {
        try {
            $sql = "SELECT * FROM cart WHERE user_id = $userId";
            $result = $this->con->query($sql);

            $cart = null;
            if ($result->num_rows > 0) {
                $cart = $result->fetch_object();

                $sqlDetails =
                    "SELECT cd.id, p.id as product_id, pd.id as prdetail_id, p.name, pd.color, pd.size, SUM(cd.quantity) AS quantity, SUM(cd.quantity * cd.price) AS price
                FROM cartdetail cd
                LEFT JOIN product p ON p.id = cd.product_id
                LEFT JOIN productdetail pd ON pd.id = cd.product_detail_id
                WHERE cd.cart_id = $cart->id AND cd.status = 1
                GROUP BY cd.product_detail_id, p.name, pd.color, pd.size
                ORDER BY p.name ASC";

                $resultDetails = $this->con->query($sqlDetails);

                $cart->cartDetailList = [];
                if ($resultDetails->num_rows > 0) {
                    while ($row = $resultDetails->fetch_object()) {
                        $cart->cartDetailList[] = $row;
                    }
                }
                return $cart;
            } else {
                throw new Exception("Giỏ hàng không tồn tại trên hệ thống!");
            }
        } catch (Exception) {
            throw new Exception("User không tồn tại trên hệ thống!");
        }
    }

    public function removeFromCart($productDetailId, $userId) {
        try {
            $sqlGetCart = "SELECT id FROM cart WHERE user_id = $userId";
            $result = $this->con->query($sqlGetCart); 
            
            if ($result->num_rows > 0) {
                $cart = $result->fetch_assoc();
                $cartId = $cart["id"];
                $sqlDeleteCartDetail = "DELETE FROM cartdetail WHERE product_id = $productDetailId AND cart_id = $cartId";
                $this->con->query($sqlDeleteCartDetail);
            }
        } catch (Exception $ex) {
            throw new Exception("SQl Error: " .$ex->getMessage());
        }
    }

    public function updateQuantity($cartDetailId, $quantity) {
        $productID = -1; $oldQuantity = 0;
        $getProductId = "SELECT cd.product_detail_id, cd.quantity FROM cartdetail cd WHERE cd.id = $cartDetailId";
        $result = $this->con->query($getProductId); 
        if ($result->num_rows > 0) {
                $cart = $result->fetch_assoc();
                $productID = $cart["product_detail_id"];
                $oldQuantity= $cart["quantity"];
         }
        if (!$this->checkQuantity($productID, $oldQuantity + $quantity)) {
            throw new Exception("Sản phẩm bạn thêm không đủ số lượng!");
        }

        try {
            $sqlCart = "UPDATE cartdetail
                        SET quantity = quantity + $quantity
                        WHERE id = $cartDetailId";
            $result = $this->con->query($sqlCart); 

            return true;
        } catch (Exception $ex) {
            throw new Exception("SQl Error: " .$ex->getMessage());
        }
    }
}
