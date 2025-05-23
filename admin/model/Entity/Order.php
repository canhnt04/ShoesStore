<?php
class Order {
    private $id;
    private $user_id ;
    private $status_id;
    private $note;
    private $created_at;
    private $updated_at;


    public function __construct($id, $user_id ,$note,$created_at, $updated_at) {
        $this->id = $id;
        $this->user_id  = $user_id ;
        $this->note = $note;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function getId() {
        return $this->id;
    }
    public function getUserId() {
        return $this->user_id ;
    }
public function setUserId($user_id){
        $this->user_id = $user_id;
}
    public function setNote($note) {
        $this->note = $note;
    }
    
    public function getNote() {
        return $this->note;
    }
    public function getStatusId() {
        return $this->status_id;
    }
    public function setStatusId($status_id) {
        $this->status_id = $status_id;
    }
   
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function getUpdatedAt() {
        return $this->updated_at;
    }
}

?>