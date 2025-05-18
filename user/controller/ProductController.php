<?php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . "/BaseController.php";
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

    public function searchProduct()
    {
        try {
            $pageNumber = $_GET['pageNumber'] ?? 1;
            $limit = 6;
            $offset = ($pageNumber - 1) * $limit;

            $keyword = $_GET['keyword'] ?? '';
            $brand = $_GET['brand'] ?? '';
            $price = $_GET['price'] ?? '';

            $productList = $this->productModel->searchProduct($keyword, $brand, $price, $limit, $offset);
            $totalPage = $this->productModel->getTotalPageSearch($keyword, $brand, $price);
            $categoryList = $this->categoryModel->getAll();

            $this->render("ProductList.php", [
                'pageNumber' => $pageNumber,
                "productList" => $productList,
                "categoryList" => $categoryList,
                'totalPage' => $totalPage,
                'paginationName' => "searchProduct",
                'keyword' => $keyword,
                'brand' => $brand,
                'price' => $price
            ]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(
                ["message" => $ex->getMessage()]
            );
        }
    }

    public function showList($params)
    {
        try {
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
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(
                ["message" => $ex->getMessage()]
            );
        }
    }

    public function showById($params)
    {
        try {
            $id = $params["id"];
            $prDetailsID = $params["pr_id"];
            $product = $this->productModel->getById($id, $prDetailsID);
            // Lấy danh sách Size theo Màu.
            $sizeList = $this->productModel->getSizeListByIdAndColor($id, $product->productDetails->color);
            // Lấy tất cả Màu theo sản phẩm cha để hiện lên color
            $colorList = $this->productModel->getColorListById($id);
            $productDetailsSelected = $product->productDetails;

            $this->render("ProductDetail.php", [
                "id" => $id, // Xét theo id và productId
                "product" => $product,
                "sizeList" => $sizeList,
                "colorList" => $colorList,
                "productDetailsSelected" => $productDetailsSelected,
                "productDetailsId"
            ]);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(
                ["message" => $ex->getMessage()]
            );
        }
    }

    public function showByCategory($params)
    {
        try {
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
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(
                ["message" => $ex->getMessage()]
            );
        }
    }
}
