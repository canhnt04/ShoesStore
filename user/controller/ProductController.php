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

    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->searchProduct($keyword);
        $categoryList = $this->categoryModel->getAll();

        $this->render("ProductList.php", [
            "productList" => $products,
            "categoryList" => $categoryList,
            "pageNumber" => 1,
            "totalPage" => 1,
            "paginationName" => "search"
        ]);
    }

    public function searchAdvanced()
    {
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : -1;
        $minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
        $maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : PHP_INT_MAX;

        $products = $this->productModel->searchProductAdvanced($keyword, $categoryId, $minPrice, $maxPrice);
        $categoryList = $this->categoryModel->getAll();

        $this->render("ProductList.php", [
            "productList" => $products,
            "categoryList" => $categoryList,
            "pageNumber" => 1,
            "totalPage" => 1,
            "paginationName" => "searchAdvanced"
        ]);
    }

    public function showList($params)
    {
        $pageNumber = $params['pageNumber'];
        $limit = 6;
        $offset = ($pageNumber - 1) * $limit;
        try {
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
        try {
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
