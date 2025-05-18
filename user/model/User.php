<?php
require_once __DIR__ . "/../../config/init.php";


class User
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function login($username, $password)
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->conn->prepare($sql);  // Chuẩn bị câu lệnh
        $stmt->bind_param("s", $username); // Gắn giá trị biến vào ?
        $stmt->execute(); // Thực thi truy vấn
        $result = $stmt->get_result();   // Lấy kết quả

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function register($username, $email, $password)
    {
        // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
        $hashPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO user (username, email, password, role_id, status, created_at, updated_at) VALUES (?, ?, ?, 4, 1, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashPassword);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            $this->createCart($userId);
            $this->createCustomer($userId);
            return true;
        }
        return false;
    }

    private function createCart($userId)
    {
        $sql = "INSERT INTO cart (user_id, status) VALUES (?, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        return $stmt->execute();
    }

    private function createCustomer($userId)
    {
        $sql = "INSERT INTO customer (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        return $stmt->execute();
    }

    public function checkUsernameExist($username)
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Nếu có bản ghi, trả về true (username đã tồn tại)
    }

    public function checkEmailExist($email)
    {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Nếu có bản ghi, trả về true (email đã tồn tại)
    }

    public function getUserByid($userId)
    {
        $sql = "SELECT 
                    cu.fullname,
                    cu.address,
                    cu.phone, 
                    us.username, 
                    us.email
                FROM customer cu 
                JOIN user us on cu.user_id = us.id
                WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? [];
    }
}
