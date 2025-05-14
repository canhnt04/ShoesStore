<?php
class Employee
{
    private $id;
    private $user_id;
    private $fullname;
    private $phone;
    private $address;
    private $salary;
    private $created_at;
    private $updated_at;

    public function __construct($id, $user_id, $fullname, $phone, $address, $salary, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->fullname = $fullname;
        $this->phone = $phone;
        $this->address = $address;
        $this->salary = $salary;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getFullname()
    {
        return $this->fullname;
    }

    public function getPhone()
    {
        return $this->phone;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getSalary()
    {
        return $this->salary;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setSalary($salary)
    {
        $this->salary = $salary;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
