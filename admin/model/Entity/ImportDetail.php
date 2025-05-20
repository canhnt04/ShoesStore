<?php

class ImportDetail
{
    private $id;
    private $productdetail_id;
    private $quantity;
    private $price;

    private $sale_price;
    private $created_at;
    private $updated_at;

    public function __construct($id, $productdetail_id, $quantity, $price, $sale_price, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->productdetail_id = $productdetail_id;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->sale_price = $sale_price;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Product Detail ID
    public function getProductDetailId()
    {
        return $this->productdetail_id;
    }

    public function setProductDetailId($productdetail_id)
    {
        $this->productdetail_id = $productdetail_id;
    }

    // Quantity
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    // Price
    public function getPrice()
    {
        return $this->price;
    }
    public function getSalePrice()
    {
        return $this->sale_price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    // Created At
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    // Updated At
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
