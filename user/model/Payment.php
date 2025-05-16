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
            return $order_id;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function showOrderById($orderId)
    {
        try {
            $sql = "SELECT od.id, od.order_id, pd.name, od.quantity, od.price
             FROM orderdetail od
             JOIN product pd on pd.id = od.product_id
             WHERE od.order_id = $orderId";

            $result = $this->con->query($sql);
            $orderDetailList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $orderDetailList[] = $row;
                }
            }
            return $orderDetailList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function showOrderList($userId)
    {
        try {
            $sql = "SELECT o.id, o.user_id, o.note, o.created_at,
                     os.name as status, pm.name as paymethod,
                     SUM(od.price * od.quantity) AS total_price
             FROM orders o
             JOIN orders_status os ON os.id = o.status_id
             JOIN payment_method pm ON pm.id = o.paymethod
             JOIN orderdetail od ON od.order_id = o.id
             WHERE o.user_id = $userId
             GROUP BY o.id";
            $result = $this->con->query($sql);
            $orderList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $orderList[] = $row;
                }
            }
            return $orderList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function showOrderListByStatus($userId, $orderStatusId)
    {
        try {
            $sql = "SELECT o.id, o.user_id, o.note, o.created_at,
                     os.name as status, pm.name as paymethod,
                     SUM(od.price * od.quantity) AS total_price
             FROM orders o
             JOIN orders_status os ON os.id = o.status_id
             JOIN payment_method pm ON pm.id = o.paymethod
             JOIN orderdetail od ON od.order_id = o.id
             WHERE o.user_id = $userId AND o.status_id = $orderStatusId
             GROUP BY o.id ORDER BY o.created_at DESC";
            $result = $this->con->query($sql);
            $orderList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $orderList[] = $row;
                }
            }
            return $orderList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }


    public function showOrderStatusList()
    {
        try {
            $sql = "SELECT os.id, os.name FROM orders_status os";
            $result = $this->con->query($sql);
            $orderStatusList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $orderStatusList[] = $row;
                }
            }
            return $orderStatusList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }
}
