<?php
include_once __DIR__ . '/../../config/init.php';
include_once __DIR__ . '/../model/Model/Model_Category.php';


class CategoryController
{
    private $model_category;

    public function __construct($connection)
    {
        $this->model_category = new Model_Category($connection);
    }

    public function countList(): int
    {
        return $this->model_category->countCategory();
    }

    public function getAllCategories()
    {
        return  $this->model_category->getAllCategories();
    }
}
