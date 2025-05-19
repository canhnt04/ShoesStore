<?php

include_once __DIR__ . '/../Entity/importreceipt.php';
include_once __DIR__ . '/../../../config/init.php';

class Model_import
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function filterImports(array $filters = [], int $limit = 5, int $offset = 0)
    {
        $params = [];
        $types = '';
        $where = [];

        if (!empty($filters['begin_date'])) {
            $where[] = 'imp.created_at >= ?';
            $params[]   = $filters['begin_date'];
            $types     .= 's';
        }

        if (!empty($filters['end_date'])) {
            $where[] = 'imp.created_at <= ?';
            $params[]   = $filters['end_date'];
            $types     .= 's';
        }

        $whereSql = count($where)
            ? 'WHERE ' . implode(' AND ', $where)
            : '';


        $query = "SELECT imp.*
            FROM importreceipt imp
            $whereSql
            ORDER BY imp.created_at DESC
            LIMIT ? OFFSET ?;
        ";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';


        $stmt = $this->connection->prepare($query);

        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
        }
        
        $stmt->bind_param($types, ...$params);
        

        $stmt->execute();
        $result = $stmt->get_result();

        $imports = [];
        while ($row = $result->fetch_assoc()) {
            $import = new ImportReceipt(
                $row['id'],
                $row['user_id'],
                $row['supplier_id'],
                $row['total_price'],
                $row['created_at'],
                $row['updated_at']
            );
            $imports[] = $import;
        }
        $stmt->close();

        return $imports;
    }


    public function countFilteredImports(array $filters = []): int
    {
        $params = [];
        $where = [];
        $types = '';

        if (!empty($filters['begin_date'])) {
            $where[] = "DATE(importreceipt.created_at) >= ?";
            $params[] = $filters['begin_date'];
            $types .= 's';
        }
        if (!empty($filters['end_date'])) {
            $where[] = "DATE(importreceipt.created_at) <= ?";
            $params[] = $filters['end_date'];
            $types .= 's';
        }

        $wherequery = count($where) ? 'WHERE ' . implode(' AND ', $where) : "";

        $query = "SELECT COUNT(*) as total FROM importreceipt $wherequery";
        $stmt = $this->connection->prepare($query);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
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

    public function getDetailsByImportId($importId)
    {
        $query = "SELECT imp.*, 
       imd.id AS importdetail_id,
       product.name AS product_name,
       productdetail.size AS product_size,
       productdetail.color AS product_color,
       productdetail.material AS product_material,
       imd.quantity AS quantity,
       imd.price AS price,
       supplier.name AS supplier_name
FROM importreceipt imp
LEFT JOIN importreceiptdetail imd ON imd.import_id = imp.id
LEFT JOIN productdetail ON productdetail.id = imd.productdetail_id
LEFT JOIN product ON product.id = productdetail.product_id
LEFT JOIN supplier ON supplier.id = imp.supplier_id
WHERE imp.id = ?
";
          
        $stmt = $this->connection->prepare($query);
        if ($stmt === false) {
            die("Lỗi prepare: " . $this->connection->error);
        }

        $stmt->bind_param("i", $importId);
        $stmt->execute();
        $result = $stmt->get_result();

        $imports = [];
        while ($row = $result->fetch_assoc()) {
            $importData = [
                "import" => new ImportReceipt(
                    $row['id'],
                    $row['user_id'],
                    $row['supplier_id'],
                    $row['total_price'],
                    $row['created_at'],
                    $row['updated_at']
                ),
                "importdetail_id"=>$row['importdetail_id'],
                "product_name" => $row['product_name'],
                "product_size" => $row['product_size'],
                "product_color" => $row['product_color'],
                "product_material" => $row['product_material'],
                "quantity" => $row['quantity'],
                "price" => $row['price'],
                "supplier_name"=>$row['supplier_name']
            ];
            $imports[] = $importData;
        }

        return $imports;
    }
    public function createImport( $user_id, $supplier_id,$total_price,$created_at,$updated_at)
    {
        $query = "INSERT INTO importreceipt (user_id, supplier_id, total_price, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->connection->error);
            return false;
        }
        $stmt->bind_param('iiiss', $user_id, $supplier_id,$total_price, $created_at, $updated_at);
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
