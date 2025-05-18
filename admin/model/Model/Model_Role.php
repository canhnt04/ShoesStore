<?php
include_once __DIR__ . '/../Entity/Role.php';
include_once __DIR__ . '/../../../config/init.php';

class Model_Role
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function countRole()
    {
        $query = "SELECT COUNT(*) as total FROM role";
        $result = $this->connection->query($query);

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllRole($limit, $offset)
    {
        $query = "SELECT * FROM role LIMIT ? OFFSET ?";
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

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $role = new Role(
                $row['id'],
                $row['name']
            );
            $roles[] = $role;
        }

        return $roles;
    }

    public function getAllRoleWithoutPagination()
    {
        $query = "SELECT * FROM role";
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

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $role = new Role(
                $row['id'],
                $row['name']
            );
            $roles[] = $role;
        }

        return $roles;
    }

    public function getRoleById($id)
    {
        $query = "SELECT * FROM role WHERE id = ?";
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
            return new Role(
                $row['id'],
                $row['name']
            );
        }

        return null;
    }

    public function createRole($name)
    {

        // Câu lệnh SQL
        $query = "INSERT INTO role (name) VALUES (?)";

        // Chuẩn bị truy vấn
        $stmt = $this->connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                return true;
            }
            $stmt->close();
        }
        return false;
    }

    public function updateRole($id, $name)
    {

        $query = "UPDATE role SET name = ?  WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Lỗi khi cập nhật role: " . $stmt->error);
            return false;
        }
    }
}
