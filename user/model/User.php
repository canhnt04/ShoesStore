<?php
require_once("Database.php");

class User
{
    private $conn;
    private $userModel;

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

        $sql = "INSERT INTO user (username, email, password, role_id, status, created_at) VALUES (?, ?, ?, 4,1, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashPassword);

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
}
