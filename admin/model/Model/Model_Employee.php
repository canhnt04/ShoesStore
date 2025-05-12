<?php
include_once __DIR__ . '/../Entity/Employee.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_Employee
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function countEmployees()
    {
        $query = "SELECT COUNT(*) as total FROM employee";
        $result = $this->connection->query($query);

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllEmployees($limit, $offset)
    {
        $query = "SELECT * FROM employee LIMIT ? OFFSET ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return [];
        }

        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return [];
        }

        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employee = new Employee(
                $row['id'],
                $row['user_id'],
                $row['fullname'],
                $row['phone'],
                $row['address'],
                $row['salary'],
                $row['created_at'],
                $row['updated_at']
            );
            $employees[] = $employee;
        }

        return $employees;
    }
}
