<?php
include_once __DIR__ . '/../Entity/Customer.php';
include_once __DIR__ . '/../../../../config/database/ConnectDB.php';

class Model_Customer
{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function getAllCustomers()
    {
        $query = "SELECT * FROM customer";
        $result = $this->connection->query($query);

        // Kiểm tra nếu truy vấn bị lỗi
        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error); // Ghi log lỗi
            return []; // Trả về mảng rỗng thay vì null
        }

        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customer = new Customer(
                $row['id'],
                $row['user_id'],
                $row['fullname'],
                $row['email'],
                $row['phone'],
                $row['address'],
                $row['created_at'],
                $row['updated_at']
            );
            $customers[] = $customer;
        }

        return $customers; // Nếu không có dữ liệu, sẽ trả về []
    }
    public function getTopCustomers($startDate, $endDate, $sortOrder = 'DESC')
    {
        // Bảo vệ sortOrder khỏi SQL Injection
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $query = "
    SELECT *
    FROM (
        SELECT 
            c.id AS customer_id,
            SUM(o.total_price) AS total_spent,
            c.fullname AS customer_name
        FROM 
            orders o
        JOIN 
            customer c ON o.customer_id = c.id
        WHERE 
            o.created_at BETWEEN ? AND ?
        GROUP BY 
            c.id
        ORDER BY 
            total_spent DESC
        LIMIT 5
    ) AS top_customers
    ORDER BY total_spent $sortOrder
";


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
