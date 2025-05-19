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
        $stmt->bind_param("sssi", $fullname, $phone, $address, $userId);
        return $stmt->execute();
        // $user = null;
        // if ($result->num_rows > 0) {
        //     $user = $result->fetch_assoc();
        // }
        // return $user;
    }
}
