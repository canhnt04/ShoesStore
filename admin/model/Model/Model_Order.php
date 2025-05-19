<?php
include_once __DIR__ . '/../Entity/Order.php';
include_once __DIR__ . '/../Entity/OrderDetail.php';
include_once __DIR__ . '/../../../config/init.php';

class Model_Order
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getOrdersPaginated(array $filters = [], int $limit = 5, int $offset = 0)
    {
        $conn = $this->connection;
        $params = [];
        $types = '';
        $where = [];

        if (!empty($filters['status'])) {
            $where[] = "orders.status_id = ?";
            $params[] = $filters['status'];
            $types .= 'i';
        }

        if (!empty($filters['begin_date'])) {
            $where[] = "(orders.created_at) >= ?";
            $params[] = $filters['begin_date'];
            $types .= 's';
        }

        if (!empty($filters['end_date'])) {
            $where[] = "(orders.created_at) <= ?";
            $params[] = $filters['end_date'];
            $types .= 's';
        }
        if (!empty($filters['district'])) {
            $where[] = "customer.address LIKE ?";
            $params[] = '%' . $filters['district'] . '%';
            $types .= 's';
        }
        if (!empty($filters['province'])) {
            $where[] = "customer.address LIKE ?";
            $params[] = '%' . $filters['province'] . '%';
            $types .= 's';
        }

        $whereSql = count($where) ? "WHERE " . implode(" AND ", $where) : "";

        $query = "SELECT orders.*, 
                         customer.fullname AS customer_name,
                         customer.phone AS customer_phone, 
                         customer.address AS customer_address,
                         orders_status.name AS status_name,
                         SUM(cartdetail.quantity * cartdetail.price) AS total_price
                  FROM orders
                  LEFT JOIN customer ON orders.user_id = customer.id
                  LEFT JOIN orders_status ON orders.status_id = orders_status.id
                  LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
                  LEFT JOIN cartdetail ON cartdetail.id = orderdetail.cartdetail_id
                  LEFT JOIN product ON product.id = cartdetail.product_id
                  LEFT JOIN productdetail ON cartdetail.product_detail_id = productdetail.id
                  $whereSql
                  GROUP BY orders.id
                  ORDER BY orders.created_at DESC
                  LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = [
                "order" => new Order(
                    id: $row['id'],
                    user_id: $row['user_id'],
                    note: $row['note'],
                    created_at: $row['created_at'],
                    updated_at: $row['updated_at'],
                ),
                "customer_name" => $row['customer_name'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "total_price" => $row['total_price']
            ];
        }

        $stmt->close();

        return $orders;
    }


    public function countFilteredOrders(array $filters = [])
    {
        $conn = $this->connection;
        $params = [];
        $types = '';
        $where = [];

        // Lọc theo trạng thái đơn hàng
        if (!empty($filters['status'])) {
            $where[] = "status_id = ?";
            $params[] = $filters['status'];
            $types .= 'i';
        }

        // Lọc theo ngày bắt đầu
        if (!empty($filters['begin_date'])) {
            $where[] = "DATE(orders.created_at) >= ?";
            $params[] = $filters['begin_date'];
            $types .= 's';
        }

        // Lọc theo ngày kết thúc
        if (!empty($filters['end_date'])) {
            $where[] = "DATE(orders.created_at) <= ?";
            $params[] = $filters['end_date'];
            $types .= 's';
        }

        // Lọc theo quận/huyện
        if (!empty($filters['district'])) {
            $where[] = "customer.address LIKE ?";
            $params[] = '%' . $filters['district'] . '%';
            $types .= 's';
        }

        // Lọc theo tỉnh/thành phố
        if (!empty($filters['province'])) {
            $where[] = "customer.address LIKE ?";
            $params[] = '%' . $filters['province'] . '%';
            $types .= 's';
        }

        // Xây dựng phần WHERE của câu truy vấn
        $whereSql = count($where) ? "WHERE " . implode(" AND ", $where) : "";

        // Câu truy vấn SQL
        $query = "SELECT COUNT(*) AS total 
                  FROM orders
                  LEFT JOIN customer ON orders.user_id = customer.id
                  $whereSql";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int)$row['total'];
    }



    public function countAllOrders(): int
    {
        $query = "SELECT COUNT(DISTINCT orders.id) AS total FROM orders";
        $result = $this->connection->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }


    public function getOrdersById($orderId)
    {
        $query = "SELECT orders.*, 
                     customer.fullname AS customer_name,
                     customer.phone AS customer_phone, 
                     customer.address AS customer_address,
                     orders_status.name AS status_name,
                     payment_method.name AS payment_method,
                     orders.total_price
              FROM orders 
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN paymethod ON orders.paymethod = payment_method.id
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


    public function getOrdersByCustomerIdAndDateRange($userId, $beginDate, $endDate)
    {
        $query = "SELECT orders.*, 
              customer.fullname AS customer_name,
              customer.phone AS customer_phone, 
              customer.address AS customer_address,
              orders_status.name AS status_name,
              payment_method.name AS payment_method,
              product.name AS product_name,
              orderdetail.id AS order_detail_id,
              cartdetail.quantity AS quantity,
              cartdetail.price AS total_price
              FROM orders 
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN payment_method ON orders.paymethod = payment_method.id
              LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
              LEFT JOIN cartdetail ON cartdetail.id = orderdetail.cartdetail_id
              LEFT JOIN product ON cartdetail.product_id = product.id
              LEFT JOIN productdetail ON cartdetail.product_detail_id = productdetail.id
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
                    user_id: $row['user_id'],
                    note: $row['note'],
                    created_at: $row['created_at'],
                    updated_at: $row['updated_at'],
                ),
                "customer_name" => $row['customer_name'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "product_name" => $row['product_name'],
                "payment_method" => $row['payment_method'],
                "quantity" => $row['quantity'],
                "total_price" => $row['total_price'],
                "order_detail_id" => $row['order_detail_id'],
            ];
            $orders[] = $orderData;
        }

        return $orders;
    }
    public function getDetailsByOrderId($orderId)
    {
        $query = "SELECT orders.*, 
              customer.fullname AS customer_name,
              customer.phone AS customer_phone, 
              customer.address AS customer_address,
              orders_status.name AS status_name,
              product.name AS product_name,
              productdetail.size AS product_size,
              productdetail.color AS product_color,
              productdetail.material AS product_material,
              orderdetail.id AS order_detail_id,
              cartdetail.quantity AS quantity,
              cartdetail.price price,
              payment_method.name AS payment_method
              FROM orders 
              LEFT JOIN customer ON orders.user_id  = customer.id
              LEFT JOIN orders_status ON orders.status_id = orders_status.id
              LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
              LEFT JOIN cartdetail ON cartdetail.id = orderdetail.cartdetail_id
              LEFT JOIN product ON product.id = cartdetail.product_id
              LEFT JOIN productdetail ON cartdetail.product_detail_id = productdetail.id
              LEFT JOIN payment_method ON orders.paymethod = payment_method.id
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
                    $row['user_id'],
                    $row['note'],
                    $row['created_at'],
                    $row['updated_at']
                ),
                "customer_name" => $row['customer_name'],
                "customer_phone" => $row['customer_phone'],
                "customer_address" => $row['customer_address'],
                "status_name" => $row['status_name'],
                "product_name" => $row['product_name'],
                "product_size" => $row['product_size'],
                "product_color" => $row['product_color'],
                "product_material" => $row['product_material'],
                "quantity" => $row['quantity'],
                "price" => $row['price'],
                "order_detail_id" => $row['order_detail_id'],
                "payment_method" => $row['payment_method'],
            ];
            $orders[] = $orderData;
        }

        return $orders;
    }
    public function getProductQuantity($orderId)
    {
        $query = "SELECT 
                productdetail.id AS detail_id,
                productdetail.size AS product_size,
                productdetail.color AS product_color,
                productdetail.material AS product_material,
                product.name AS product_name,
                cartdetail.quantity AS ordered_quantity,
                productdetail.quantity AS product_quantity
              FROM orders 
              LEFT JOIN orderdetail ON orders.id = orderdetail.order_id
              LEFT JOIN cartdetail ON orderdetail.cartdetail_id = cartdetail.id
              LEFT JOIN product On product.id = cartdetail.product_id
              LEFT JOIN productdetail ON productdetail.id = cartdetail.product_detail_id
              WHERE orders.id = ?";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "detail_id" => $row['detail_id'],
                "product_size"=>$row['product_size'],
                "product_color"=>$row['product_color'],
                "product_material"=>$row['product_material'],
                "product_name" => $row['product_name'],
                "ordered_quantity" => (int)$row['ordered_quantity'],
                "product_quantity" => (int)$row['product_quantity']
            ];
        }

        return $data;
    }
    public function updateAmountProduct($orderId)
    {
        $query = "UPDATE productdetail pd 
        JOIN cartdetail cd ON cd.product_detail_id = pd.id
        JOIN orderdetail od ON cd.id = od.cartdetail_id
        JOIN orders o ON od.order_id = o.id
        SET pd.quantity = pd.quantity - cd.quantity
        WHERE o.id = ?
        AND pd.quantity >= cd.quantity
    ";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
    }
    public function restoreAmountProduct($orderId)
    {
        $query = "UPDATE productdetail pd 
        JOIN cartdetail cd ON cd.product_detail_id = pd.id
        JOIN orderdetail od ON cd.id = od.cartdetail_id
        JOIN orders o ON od.order_id = o.id
        SET pd.quantity = pd.quantity + cd.quantity
        WHERE o.id = ?
    ";

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
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
