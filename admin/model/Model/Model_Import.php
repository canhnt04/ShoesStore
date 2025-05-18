<?php

include_once __DIR__ . '/../Entity/importreceipt.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_import
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function filterImports(array $filters = [], int $limit = 5, int $offset = 0): array
    {
        $params = [];
        $types = '';
        $where = '';

        if (!empty($filters['begin_date']) && !empty($filters['end_date'])) {
            $where = 'WHERE imp.created_at BETWEEN ? AND ?';
            $params[] = $filters['begin_date'];
            $params[] = $filters['end_date'];
            $types .= 'ss';
        }

        $totalRows = $this->countFilteredImports($filters); // Gọi luôn hàm đã có

        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql = "
            SELECT imp.*
            FROM importreceipt imp
            $where
            ORDER BY imp.created_at DESC
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $this->connection->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $imports = [];
        while ($row = $result->fetch_assoc()) {
            $import = new ImportReceipt(
                $row['id'],
                $row['user_id'],
                $row['supplier_id'],
                $row['total_price'],
                $row['sale_price'],
                $row['created_at'],
                $row['updated_at']
            );
            $imports[] = $import;
        }
        $stmt->close();

        return [
            'imports' => $imports,
            'totalRows' => $totalRows
        ];
    }


    public function countFilteredImports(array $filters = []): int
    {
        $conn = $this->connection;
        $params = [];
        $where = [];

        if (!empty($filters['begin_date']) && !empty($filters['end_date'])) {
            $where[] = 'created_at BETWEEN ? AND ?';
            $params[] = $filters['begin_date'];
            $params[] = $filters['end_date'];
        }

        $whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : "";

        $query = "SELECT COUNT(*) as total FROM importreceipt $whereSQL";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare count query failed: " . $conn->error);
            return 0;
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            if (!$stmt->bind_param($types, ...$params)) {
                error_log("Bind param count query failed: " . $stmt->error);
                return 0;
            }
        }

        if (!$stmt->execute()) {
            error_log("Execute count query failed: " . $stmt->error);
            return 0;
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['total'] ?? 0;
    }

    public function createImport(int $user_id, int $supplier_id)
    {
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO importreceipt (user_id, supplier_id, total_price, created_at, updated_at)
                VALUES (?, ?, 0, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->connection->error);
            return false;
        }
        $stmt->bind_param('iiss', $user_id, $supplier_id, $now, $now);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        } else {
            error_log("Execute failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
}
