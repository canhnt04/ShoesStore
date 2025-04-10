<?php
/* Controller ProductController sẽ được gọi bởi router nếu đúng định tuyến uri */

require_once __DIR__ . "/../controller/BaseController.php";
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Category.php";

class ProductController extends BaseController
{
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function showList($params)
    {
        $pageNumber = $params['pageNumber'];
        $limit = 6;
        $offset = ($pageNumber - 1) * $limit;
        $totalPage = $this->productModel->getTotalPage();
        $productList = $this->productModel->getAll($limit, $offset);
        $categoryList = $this->categoryModel->getAll();
        
        include(__DIR__ . "../../view/ProductList.php");
    }

    public function showById($params) {
        $id = $params['id'];
        $product = $this->productModel->getById($id);
        include(__DIR__ . "../../view/ProductDetail.php");
    }

    public function showByCategory($params) {
        $pageNumber = $params['pageNumber'];
        $categoryId = (int)$params['category'];
        $totalPage = $this->productModel->getTotalPage($categoryId);
        $limit = 6;
        $offset = ($pageNumber - 1) * $limit;
        $productList = $this->productModel->getByCategory($categoryId, $limit, $offset);
        $categoryList = $this->categoryModel->getAll();
        include(__DIR__ . "../../view/ProductList.php");
    }
}
?>