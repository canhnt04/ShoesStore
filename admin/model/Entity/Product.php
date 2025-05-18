<?php
class Product
{
    private $id;
    private $name;
    private $thumbnail;
    private $supplier_id;
    private $category_id;
    private $brand;
    private $status;
    private $created_at;
    private $updated_at;

    public function __construct($id, $name, $thumbnail, $category_id, $supplier_id, $brand, $status, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->thumbnail = $thumbnail;
        $this->category_id = $category_id;
        $this->supplier_id = $supplier_id;
        $this->brand = $brand;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    // Getter methods
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getBrand()
    {
        return $this->brand;
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

    // Setter methods
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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
