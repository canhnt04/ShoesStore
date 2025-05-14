<?php
class ProductDetail
{
    private $id;
    private $product_id;
    private $description;
    private $quantity;
    private $size;
    private $color;
    private $material;
    private $brand;
    private $price;
    private $update_at;

    // Constructor
    public function __construct($id, $product_id, $description, $quantity, $size, $color, $material, $brand, $price, $update_at)
    {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->size = $size;
        $this->color = $color;
        $this->material = $material;
        $this->brand = $brand;
        $this->price = $price;
        $this->update_at = $update_at;
    }

    // Getter and Setter methods
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getMaterial()
    {
        return $this->material;
    }

    public function setMaterial($material)
    {
        $this->material = $material;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getUpdateAt()
    {
        return $this->update_at;
    }

    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
    }
}
