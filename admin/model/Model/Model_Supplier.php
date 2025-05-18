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


    public function getListSuppliers(array $filters = [], int $perPage = 5, int $page = 1): array
    {
        // Tính offset dựa vào trang hiện tại và số phần tử trên trang
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        // Lấy dữ liệu và tổng số bản ghi
        $result = $this->filterSuppliers($filters, $perPage, $offset);

        $totalRows = $result['totalRows'] ?? 0;
        $suppliers = $result['suppliers'] ?? [];

        // Tính tổng số trang (làm tròn lên)
        $totalPages = ($totalRows > 0) ? (int) ceil($totalRows / $perPage) : 1;

        return [
            'suppliers'   => $suppliers,
            'totalPages'  => $totalPages,
        ];
    }

    public function filterSuppliers(array $filters = [], int $limit = 5, int $offset = 0): array
    {
        $params = [];
        $types = '';
        $where = '';

        // Lọc theo tên nhà cung cấp nếu có
        if (!empty($filters['name'])) {
            $where = 'WHERE name LIKE ?';
            $params[] = '%' . $filters['name'] . '%';
            $types .= 's';
        }

        // Gọi hàm đếm tổng dòng
        $totalRows = $this->countFilteredSuppliers($filters);

        $sql = "
            SELECT * FROM supplier
            $where
            ORDER BY created_at DESC
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("SQL prepare error: " . $this->connection->error);
        }

        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = new Supplier(
                $row['id'],
                $row['name'],
                $row['address'],
                $row['phone'],
                $row['email'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        $stmt->close();

        return [
            'suppliers' => $suppliers,
            'totalRows' => $totalRows
        ];
    }

    public function countFilteredSuppliers(array $filters = []): int
    {
        $params = [];
        $types = '';
        $where = '';

        if (!empty($filters['name'])) {
            $where = 'WHERE name LIKE ?';
            $params[] = '%' . $filters['name'] . '%';
            $types .= 's';
        }

        $sql = "SELECT COUNT(*) as total FROM supplier $where";

        $stmt = $this->connection->prepare($sql);
      

        if (!$stmt) {
            die("COUNT SQL error: " . $this->connection->error);
        }
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['total'];
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
