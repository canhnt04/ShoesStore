<?php
include_once __DIR__ . '/../Entity/Customer.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_Customer
{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function getAllCustomers()
    {
        $query = "SELECT customer.*,
        user.email AS email
        FROM customer
        LEFT JOIN user ON customer.user_id = user.id
        ";
        $result = $this->connection->query($query);

        // Kiểm tra nếu truy vấn bị lỗi
        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error); // Ghi log lỗi
            return []; // Trả về mảng rỗng thay vì null
        }

        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customerData = [
                "customer" => new Customer(
                    id: $row['id'],
                    user_id: $row['user_id'],
                    fullname: $row['fullname'],
                    phone: $row['phone'],
                    address: $row['address'],
                    created_at: $row['created_at'],
                    updated_at: $row['updated_at'],
                ),
                "email" => $row['email'],

            ];
            $customers[] = $customerData;
        }

        return $customers; // Nếu không có dữ liệu, sẽ trả về []
    }
    public function getTopCustomers($startDate, $endDate, $sortOrder)
    {
        // Bảo vệ sortOrder khỏi SQL Injection
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $query = "SELECT *
        FROM (
            SELECT 
                customer.id AS user_id,
                customer.fullname AS customer_name,
                SUM(od.quantity * od.price) AS total_spent
            FROM orders 
            JOIN customer ON orders.user_id = customer.id
            JOIN orderdetail od ON orders.id = od.order_id
            WHERE orders.created_at BETWEEN ? AND ? AND orders.status_id =5
            GROUP BY customer.id
            ORDER BY total_spent DESC
            LIMIT 5
        ) AS top_customers
        ORDER BY total_spent $sortOrder";


        $stmt = $this->connection->prepare($query);
        if ($stmt === false) {
            throw new Exception("Lỗi prepare: " . $this->connection->error);
        }

        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }

        return $customers;
    }
}
