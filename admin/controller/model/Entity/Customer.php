<?php
class Customer {
    private $id;

    private $user_id;
    private $fullname;
    private $email;
    private $phone;
    private $address;

    private $created_at;
    private $updated_at;

    public function __construct($id, $user_id, $fullname, $email, $phone, $address, $created_at, $updated_at) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId() {
        return $this->id;
    }
    public function getUserId() {
        return $this->user_id;
    }
    public function getFullName() {
        return $this->fullname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }
}
?>