<?php
include_once __DIR__ . '/../Entity/User.php';
include_once __DIR__ . '/../../../config/init.php';

class Model_User
{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function countEmployeeUsers()
    {
        $query = "SELECT COUNT(*) as total FROM user WHERE role_id NOT IN (?, ?)";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return 0;
        }

        $excludedRole1 = 1;
        $excludedRole2 = 4;

        $stmt->bind_param("ii", $excludedRole1, $excludedRole2);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function countCustomerUsers()
    {
        $query = "SELECT COUNT(*) as total FROM user WHERE role_id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return 0;
        }

        $roleId = 4;
        $stmt->bind_param("i", $roleId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }


    public function getAllEmployeeUser($limit, $offset)
    {
        $query = "SELECT * FROM user WHERE role_id NOT IN (?, ?) LIMIT ? OFFSET ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return [];
        }

        $excludedId1 = 1;
        $excludedId2 = 4;

        $stmt->bind_param("iiii", $excludedId1, $excludedId2, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return [];
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new User(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['email'],
                $row['role_id'],
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
            $users[] = $user;
        }

        return $users;
    }


    public function getAllCustomUser($limit, $offset)
    {
        $excludedId = 4;

        $query = "SELECT * FROM user WHERE role_id = ? LIMIT ? OFFSET ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return [];
        }

        $stmt->bind_param("iii", $excludedId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return [];
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new User(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['email'],
                $row['role_id'],
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
            $users[] = $user;
        }

        return $users;
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM user WHERE id = ?";
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
            return new User(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['email'],
                $row['role_id'],
                $row['status'],
                $row['created_at'],
                $row['updated_at']
            );
        }

        return null;
    }


    public function createUser($username, $password, $email, $role_id, $status)
    {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Băm mật khẩu
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Câu lệnh SQL
        $query = "INSERT INTO user (username, password, email, role_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Chuẩn bị truy vấn
        $stmt = $this->connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sssisss", $username, $hashed_password, $email, $role_id, $status, $created_at, $updated_at);
            if ($stmt->execute()) {
                return true;
            }
            $stmt->close();
        }
        return false;
    }

    public function updateUser($id, $role_id)
    {
        $query = "UPDATE user SET role_id = ?  WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn: " . $this->connection->error);
            return false;
        }

        $stmt->bind_param("ii", $role_id, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Lỗi khi cập nhật role: " . $stmt->error);
            return false;
        }
    }

    public function deleteUser($id, $value)
    {
        $query = "UPDATE user SET status = $value WHERE id = ?";
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
