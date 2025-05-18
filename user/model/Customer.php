<?php
require_once __DIR__ . "/../../config/init.php";


class Customer
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function updateUserInfo($userId, $fullname, $phone, $address)
    {
        $sql = "UPDATE customer SET fullname = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("sssi", $fullname, $phone, $address, $userId);

        $result = $stmt->execute();

        if (!$result) {
            die("Execute failed: " . $stmt->error);
        }

        return $result;
    }
}
