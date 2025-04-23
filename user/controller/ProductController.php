<?php
/* Controller ProductController sẽ được gọi bởi router nếu đúng định tuyến uri */
require_once(__DIR__ . "../BaseController.php");
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Category.php";
require_once __DIR__ . "/../model/Cart.php";

class ProductController extends BaseController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
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

        $this->render("ProductList.php", [
            'pageNumber' => $pageNumber,
            'productList' => $productList,
            'categoryList' => $categoryList,
            'totalPage' => $totalPage,
            'paginationName' => "showList"
        ]);
    }

    public function showById($params)
    {
        $id = $params['id'];
        $color = $params['color'];
        $product = $this->productModel->getById($id);
        $productDetailsSelected = null;
        foreach ($product->productDetailsList as $productDetails) {
            if ($productDetails->color === $color) {
                $productDetailsSelected = $productDetails;
                break;
            }
        }
        $this->render("ProductDetail.php", [
            "id" => $id,
            "color" => $color,
            "product" => $product,
            "productDetailsSelected" => $productDetailsSelected
        ]);
    }

    public function showByCategory($params)
    {
        $pageNumber = $params['pageNumber'];
        $categoryId = (int)$params['category'];
        $totalPage = $this->productModel->getTotalPage($categoryId);
        $limit = 6;
        $offset = ($pageNumber - 1) * $limit;
        $productList = $this->productModel->getByCategory($categoryId, $limit, $offset);
        $categoryList = $this->categoryModel->getAll();

        $this->render("ProductList.php", [
            "pageNumber" => $pageNumber,
            "categoryId" => $categoryId,
            "totalPage" => $totalPage,
            "productList" => $productList,
            "categoryList" => $categoryList,
            "paginationName" => "showByCategory"
        ]);
    }


}
