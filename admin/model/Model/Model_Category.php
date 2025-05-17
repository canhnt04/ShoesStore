<?php
include_once __DIR__ . '/../Entity/Category.php';
include_once __DIR__ . '/../../../config/database/ConnectDB.php';

class Model_Category
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function countCategory()
    {
        $query = "SELECT COUNT(*) as total FROM category";
        $result = $this->connection->query($query);

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Lấy tất cả danh mục
    public function getAllCategories()
    {
        $query = "SELECT * FROM category";
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

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $category = new Category(
                $row['id'],
                $row['name'],
                $row['created_at'],
                $row['updated_at']
            );
            $categories[] = $category;
        }

        return $categories;
    }

    // Lấy tên danh mục theo ID
    public function getCategoryNameById($id)
    {
        $query = "SELECT name FROM category WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name'] : null;
    }
}
