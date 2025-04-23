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

    public function addToCart($productDetails, $userId, $cartId)
    {
        try {
            $sqlCartDetails =
                "INSERT INTO cartdetail (cart_id, product_id, quantity, price, product_detail_id, status)
                 VALUES ($cartId, $productDetails->product_id, $productDetails->quantity, $productDetails->price, $productDetails->id, 1)";

            $this->con->query($sqlCartDetails);

            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("Thêm giỏ hàng thất bại! Vui lòng thử lại sau.");
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
                    "SELECT p.name, pd.color, SUM(cd.quantity) AS quantity, SUM(cd.quantity * cd.price) AS price
                FROM cartdetail cd
                LEFT JOIN product p ON p.id = cd.product_id
                LEFT JOIN productdetail pd ON pd.id = cd.product_detail_id
                WHERE cd.cart_id = $cart->id AND cd.status = 1
                GROUP BY cd.product_detail_id, p.name, pd.color
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
                throw new Exception("Lấy giỏ hàng thất bại!");
            }
        } catch (Exception) {
            throw new Exception("User không tồn tại hoặc chưa đăng nhập!");
        }
    }
}
