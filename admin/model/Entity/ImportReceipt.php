<?php
class ImportReceipt
{
    private $id;
    private $user_id;
    private $supplier_id;
    private $created_at;
    private $updated_at;

    private $total_price;

    private $sale_price;

    public function __construct($id, $user_id, $supplier_id, $total_price, $sale_price, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->supplier_id = $supplier_id;
        $this->total_price = $total_price;
        $this->sale_price = $sale_price;
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
    public function getSupplierId()
    {
        return $this->supplier_id;
    }
    public function getTotalPrice(){
        return $this->total_price;
    }
    public function getSalePrice(){
        return $this->sale_price;
    }
    public function getCreatedAt(){
        return $this->created_at;
    }
    public function getUpdateAt(){
        return $this->updated_at;
    }
}
