<?php
include_once __DIR__ . '/../Entity/ProductDetail.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_ProductDetail
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function countProductDetails()
    {
        $query = "SELECT COUNT(*) as total FROM productdetail";
        $result = $this->connection->query($query);

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllProductDetails($limit, $offset)
    {
        $query = "SELECT * FROM productdetail LIMIT ? OFFSET ?";
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

        $productDetails = [];
        while ($row = $result->fetch_assoc()) {
            $detail = new ProductDetail(
                $row['id'],
                $row['product_id'],
                $row['description'],
                $row['quantity'],
                $row['size'],
                $row['color'],
                $row['material'],
                $row['price'],
                $row['status'],
                $row['updated_at']
            );
            $productDetails[] = $detail;
        }

        return $productDetails;
    }

    public function createProductDetail($product_id, $description, $quantity, $size, $color, $material, $price)
    {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO productdetail (product_id, description, quantity, size, color, material, price, created_at, updated_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param("isisssdss", $product_id, $description, $quantity, $size, $color, $material, $price, $created_at, $updated_at);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }

            $stmt->close();
        }

        return false;
    }


    public function updateProductDetail($size, $quantity, $color, $material, $price, $id)
    {

        $query = "UPDATE productdetail SET size = ?, quantity = ?, color = ?, material = ?, price = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("ssissdi", $size, $quantity, $color, $material, $price, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Lỗi khi cập nhật sản phẩm: " . $stmt->error);
            return false;
        }
    }
}
