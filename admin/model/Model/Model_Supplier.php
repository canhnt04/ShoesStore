<?php
include_once __DIR__ . '/../Entity/Supplier.php';
include_once __DIR__ . '/../../../config/init.php';

class Model_Supplier
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Lấy tất cả nhà cung cấp
    public function getAllSuppliers()
    {
        $query = "SELECT * FROM supplier";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return [];
        }

        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $supplier = new Supplier(
                $row['id'],
                $row['name'],
                $row['phone'],
                $row['email'],
                $row['address'],
                $row['created_at'],
                $row['updated_at']
            );
            $suppliers[] = $supplier;
        }

        return $suppliers;
    }

    // Lấy tên nhà cung cấp theo ID
    public function getSupplierNameById($id)
    {
        $query = "SELECT name FROM supplier WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name'] : null;
    }
}
