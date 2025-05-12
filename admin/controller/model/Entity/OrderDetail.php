<?php
class OrderDetail
{
    private $id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $price;

    public function __construct($id, $order_id, $product_id, $quantity, $price)
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getProductId()
    {
        return $this->product_id;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }
    public function getOrderId()
    {
        return $this->order_id;
    }
}
?>    