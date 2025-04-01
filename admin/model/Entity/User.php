<?php
class User
{
    private $id;
    private $name;
    private $password;
    private $email;
    private $role_id;
    private $status;
    private $created_at;
    private $updated_at;

    public function __construct($id, $name, $password, $email, $role_id, $status, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->email = $email;
        $this->role_id = $role_id;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->name;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRoleId()
    {
        return $this->role_id;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
