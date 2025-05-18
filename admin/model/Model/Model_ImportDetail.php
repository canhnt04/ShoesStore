<?php

include_once __DIR__ . '/../Entity/importreceipt.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_importdetail
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function createImportDetail($import_id, $productdetail_id, $quantity, $price, $sale_price,$created_at, $updated_at)
    {
        $query = "INSERT INTO importreceiptdetail
                  (import_id, productdetail_id, quantity, price,sale_price, created_at, updated_at)
                  VALUES (?, ?, ?, ?, ?, ?,?)";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            return ['success' => false, 'message' => "Prepare failed: " . $this->connection->error];
        }

        $stmt->bind_param('iiiddss', $import_id, $productdetail_id, $quantity, $price,$sale_price, $created_at, $updated_at);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'message' => "Execute failed: " . $error];
        }

        $newId = $this->connection->insert_id;
        $stmt->close();

        return ['success' => true, 'id' => $newId];
    }
}
