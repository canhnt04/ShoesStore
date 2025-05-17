<?php
include_once __DIR__ . '/../Entity/Product.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_Product
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function countProducts()
    {
        $query = "SELECT COUNT(*) as total FROM product";
        $result = $this->connection->query($query);

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllProductsWithoutPagination()
    {
        $query = "SELECT * FROM product";
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

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $product = new Product(
                $row['id'],
                $row['name'],
                $row['thumbnail'],
                $row['supplier_id'],
                $row['category_id'],
                $row['brand'],
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
            $products[] = $product;
        }

        return $products;
    }

    public function getAllProducts($limit, $offset)
    {
        $query = "SELECT * FROM product LIMIT ? OFFSET ?";
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

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $product = new Product(
                $row['id'],
                $row['name'],
                $row['thumbnail'],
                $row['category_id'],
                $row['supplier_id'],
                $row['brand'],
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
            $products[] = $product;
        }

        return $products;
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM product WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return null;
        }

        if ($row = $result->fetch_assoc()) {
            return new Product(
                $row['id'],
                $row['name'],
                $row['thumbnail'],
                $row['supplier_id'],
                $row['category_id'],
                $row['brand'],
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        return null;
    }

    public function createProduct($name, $thumbnail, $category_id, $supplier_id, $brand, $status)
    {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Câu lệnh SQL
        $query = "INSERT INTO product (name, thumbnail, category_id, supplier_id, brand, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Chuẩn bị truy vấn
        $stmt = $this->connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssiissss", $name, $thumbnail, $category_id, $supplier_id, $brand, $status, $created_at, $updated_at);
            if ($stmt->execute()) {
                return true;
            }
            $stmt->close();
        }
        return false;
    }

    public function updateProduct($id, $name, $thumbnail, $category_id)
    {
        $updated_at = date('Y-m-d H:i:s');

        $query = "UPDATE product SET name = ?, thumbnail = ?, category_id = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("sssi", $name, $thumbnail, $category_id, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Lỗi khi cập nhật sản phẩm: " . $stmt->error);
            return false;
        }
    }
    public function deleteProduct($id, $value)
    {
        $query = "UPDATE product SET status = $value WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Lỗi khi cập nhật trạng thái sản phẩm: " . $stmt->error);
            return false;
        }
    }
}
