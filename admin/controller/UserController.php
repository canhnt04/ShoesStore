<?php
include_once __DIR__ . '/../../config/init.php';
include_once __DIR__ . '/../model/Model/Model_User.php';
class UserController
{
    private $userModel;

    public function __construct($connection)
    {
        $this->userModel = new Model_User($connection);
    }

    public function countEmployeeUsers()
    {
        return $this->userModel->countEmployeeUsers();
    }

    public function countCustomerUsers()
    {
        return $this->userModel->countCustomerUsers();
    }

    public function listEmployeeUsers($limit, $offset)
    {
        return $this->userModel->getAllEmployeeUser($limit, $offset);
    }

    public function listCustomerUsers($limit, $offset)
    {
        return $this->userModel->getAllCustomUser($limit, $offset);
    }

    public function createUser($username, $password, $email, $role_id)
    {
        return $this->userModel->createUser($username, $password, $email, $role_id, 1);
    }

    public function updateUser($id, $role_id)
    {
        return $this->userModel->updateUser($id, $role_id);
    }

    public function toggleUserStatus($id, $value)
    {
        return $this->userModel->deleteUser($id, $value);
    }

    public function getUserById($id)
    {
        return $this->userModel->getUserById($id);
    }
}
