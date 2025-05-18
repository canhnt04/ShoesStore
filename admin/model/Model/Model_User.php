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

    public function countUsers()
    {
        $query = "SELECT COUNT(*) as total FROM user";
        $result = $this->connection->query($query);

        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error);
            return 0;
        }

        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getAllUsers($limit, $offset)
    {
        $query = "SELECT * FROM user LIMIT ? OFFSET ?";
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
}
