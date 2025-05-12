<?php
include_once __DIR__ . '/../Entity/User.php';
include_once __DIR__ . '/../../../../config/database/ConnectDB.php';

class Model_User
{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function getAllUsers()
    {
        $query = "SELECT * FROM user";
        $result = $this->connection->query($query);

        // Kiểm tra nếu truy vấn bị lỗi
        if (!$result) {
            error_log("Lỗi truy vấn: " . $this->connection->error); // Ghi log lỗi
            return []; // Trả về mảng rỗng thay vì null
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

        return $users; // Nếu không có dữ liệu, sẽ trả về []
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
