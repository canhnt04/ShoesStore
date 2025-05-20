<?php
include_once __DIR__ . '/../model/Model/Model_Role.php';


class RoleController
{
    private $model_role;

    public function __construct($connection)
    {
        $this->model_role = new Model_Role($connection);
    }

    public function countList(): int
    {
        return $this->model_role->countRole();
    }

    public function getAllRoles($limit, $offset)
    {
        return $this->model_role->getAllRole($limit, $offset);
    }

    public function getRoleById($id)
    {
        return $this->model_role->getRoleById($id);
    }

    public function getAllRolesWithoutPagination()
    {
        return  $this->model_role->getAllRoleWithoutPagination();
    }

    public function addRole($name)
    {
        return $this->model_role->createRole($name);
    }

    public function updateRole($id, $name)
    {
        return $this->model_role->updateRole($id, $name);
    }
}
