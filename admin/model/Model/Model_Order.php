<?php
include_once __DIR__ . '/../Entity/Order.php';
include_once __DIR__ . '/../Entity/OrderDetail.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_Order
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getAllOrders()
    {
        $query = "SELECT orders.*, 
                  customer.fullname AS customer_name,
                  customer.gmail AS customer_email, 
                  customer.phone AS customer_phone, 
                  customer.address AS customer_address,
                  orders_status.name AS status_name,
                  product.name AS product_name,
                  orderdetail.quantity AS quantity,
                  SUM(orderdetail.quantity * orderdetail.price) AS total_price
                  FROM orders 
                  LEFT JOIN customer ON orders.user_id  = customer.id
                  LEFT JOIN orders_status ON orders.status_id = orders_status.id
                  LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
                  LEFT JOIN product ON orderdetail.product_id = product.id
                  GROUP BY orders.id
                  ";

        $result = $this->connection->query($query);
        if (!$result) {
            die("Lỗi truy vấn: " . $this->connection->error);
        }

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orderData = [
                "order" => new Order(
                    id: $row['id'],
                    user_id : $row['user_id'],
                    note: $row['note'],
                    paymethod: $row['paymethod'],
                    created_at: $row['created_at'],
                    updated_at: $row['updated_at'],


                ),
                "customer_name" => $row['customer_name'],
                "customer_email" => $row['customer_email'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "product_name" => $row['product_name'],
                "quantity" => $row['quantity'],
                "total_price"=>$row['total_price']
            ];
            $orders[] = $orderData;
        }
        return $orders;
    }
    public function getOrdersById($orderId)
    {
        $query = "SELECT orders.*, 
                     customer.fullname AS customer_name,
                     customer.gmail AS customer_email, 
                     customer.phone AS customer_phone, 
                     customer.address AS customer_address,
                     orders_status.name AS status_name,
                     orders.paymethod AS paymethod_name,
                     orders.total_price
              FROM orders 
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN paymethod ON orders.payment_id = paymethod.id
              WHERE orders.id = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Trả về 1 dòng dạng mảng kết hợp
    }

    public function updateStatus($orderId, $newStatus)
    {
        $stmt = $this->connection->prepare("UPDATE orders SET status_id = ? WHERE id = ?");
        if (!$stmt) {
            die("Lỗi prepare: " . $this->connection->error);
        }
        $stmt->bind_param("ii", $newStatus, $orderId);
        return $stmt->execute();
    }

    public function filterOrders($status = '', $beginDate = '', $endDate = '', $district = '', $province = '')
    {
        $query = "SELECT orders.*, 
                     customer.fullname AS customer_name,
                     customer.gmail AS customer_email, 
                     customer.phone AS customer_phone, 
                     customer.address AS customer_address,
                     orders_status.name AS status_name,
                     paymethod.name AS paymethod_name,
                     product.name AS product_name,
                     orderdetail.quantity AS quantity,
                     orderdetail.price AS total_price
              FROM orders
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN paymethod ON orders.payment_id = paymethod.id
              LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
              LEFT JOIN product ON orderdetail.product_id = product.id
              WHERE 1=1";

        $params = [];

        if ($status) {
            $query .= " AND orders.status_id = ?";
            $params[] = $status;
        }

        if ($beginDate && $endDate) {
            $query .= " AND orders.created_at BETWEEN ? AND ?";
            $params[] = $beginDate;
            $params[] = $endDate;
        }

        if ($district) {
            $query .= " AND customer.address LIKE ?";
            $params[] = "%" . $district . "%";
        }

        if ($province) {
            $query .= " AND customer.address LIKE ?";
            $params[] = "%" . $province . "%";
        }

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            die("Lỗi chuẩn bị câu lệnh: " . $this->connection->error);
        }

        if (!empty($params)) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orderData = [
                "order" => new Order(
                    $row['id'],
                    $row['user_id '],
                    $row['note'],
                    $row['paymethod'], // Assuming 'paymethod' is the missing argument
                    $row['created_at'],
                    $row['updated_at']
                ),
                "customer_name" => $row['customer_name'],
                "customer_email" => $row['customer_email'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "paymethod_name" => $row['paymethod_name'],
                "product_name" => $row['product_name'],
                "quantity" => $row['quantity'],
                "total_price" => $row['total_price'],
            ];
            $orders[] = $orderData;
        }
        return $orders;
    }
    public function getOrdersByCustomerIdAndDateRange($userId, $beginDate, $endDate)
    {
        $query = "SELECT orders.*, 
              customer.fullname AS customer_name,
              customer.gmail AS customer_email, 
              customer.phone AS customer_phone, 
              customer.address AS customer_address,
              orders_status.name AS status_name,
              orders.paymethod AS paymethod_name,
              product.name AS product_name,
              orderdetail.id AS order_detail_id,
              orderdetail.quantity AS quantity,
              orderdetail.price AS total_price
              FROM orders 
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN paymethod ON orders.payment_id = paymethod.id
              LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
              LEFT JOIN product ON orderdetail.product_id = product.id
              WHERE orders.user_id  = ? 
              AND orders.created_at BETWEEN ? AND ?
              ORDER BY orders.created_at DESC";

        $stmt = $this->connection->prepare($query);
        if ($stmt === false) {
            die("Lỗi prepare: " . $this->connection->error);
        }

        $stmt->bind_param("iss", $userId, $beginDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orderData = [
                "order" => new Order(
                    id: $row['id'],
                    user_id : $row['user_id '],
                    note: $row['note'],
                    paymethod: $row['paymethod'],
                    created_at: $row['created_at'],
                    updated_at: $row['update_at'],
                ),
                "customer_name" => $row['customer_name'],
                "customer_email" => $row['customer_email'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "product_name" => $row['product_name'],
                "quantity" => $row['quantity'],
                "total_price" => $row['total_price'],
                "order_detail_id" => $row['order_detail_id'],
            ];
            $orders[] = $orderData;
        }

        return $orders;
    }
    public function getOrdersByOrderId($orderId)
    {
        $query = "SELECT orders.*, 
              customer.fullname AS customer_name,
              customer.gmail AS customer_email, 
              customer.phone AS customer_phone, 
              customer.address AS customer_address,
              orders_status.name AS status_name,
              product.name AS product_name,
              orderdetail.id AS order_detail_id,
              orderdetail.quantity AS quantity,
              orderdetail.price AS total_price
              FROM orders 
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN paymethod ON orders.payment_id = paymethod.id
              LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
              LEFT JOIN product ON orderdetail.product_id = product.id
              WHERE orders.id = ?";

        $stmt = $this->connection->prepare($query);
        if ($stmt === false) {
            die("Lỗi prepare: " . $this->connection->error);
        }

        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orderData = [
                "order" => new Order(
                    $row['id'],
                    $row['user_id '],
                    $row['note'],
                    $row['paymethod'], // Assuming 'paymethod' is the missing argument
                    $row['created_at'],
                    $row['updated_at']
                ),
                "customer_name" => $row['customer_name'],
                "customer_email" => $row['customer_email'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "product_name" => $row['product_name'],
                "quantity" => $row['quantity'],
                "total_price" => $row['total_price'],
                "order_detail_id" => $row['order_detail_id'],
            ];
            $orders[] = $orderData;
        }

        return $orders;
    }
    public function createOrder($user_id, $note, $status_id, $created_at, $updated_at)
    {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        // Câu lệnh SQL
        $query = "INSERT INTO `order` (user_id, note, status_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
        // Chuẩn bị truy vấn
        $stmt = $this->connection->prepare($query);
        if ($stmt) {
            $stmt->bind_param("issss", $user_id, $note, $status_id, $created_at, $updated_at);
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Lỗi thực thi truy vấn: " . $stmt->error); // Ghi log lỗi
                return false;
            }
        } else {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error); // Ghi log lỗi
            return false;
        }
    }
}


