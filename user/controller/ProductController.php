<?php
/* Controller ProductController sẽ được gọi bởi router nếu đúng định tuyến uri */

require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/Category.php";
require_once __DIR__ . "/../model/Cart.php";

class ProductController
{
    private $productModel;
    private $categoryModel;
    private $cartModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->cartModel = new Cart();
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
        $color = $params['color'];
        $product = $this->productModel->getById($id);
        $productDetailsSelected = null;
        foreach ($product->productDetailsList as $productDetails) {
            if ($productDetails->color === $color) {
                $productDetailsSelected = $productDetails;
                break;
            }
        }
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

    public function buyProduct($params) {
        // Phần tử thuộc $GET trong params: page=Product&action=buyProduct
        // $POST trong params: prdetail_id, pr_id, product-size, ...
        $productDetails = new stdClass(); // Tạo đỡ cái entity lỏ
        $productDetails->id = $params['prdetail_id'];
        $productDetails->product_id = $params['pr_id'];
        $productDetails->size = $params['product-size'];
        $productDetails->quantity = $params['product-quanity'];
        // // $productDetails->quantity = $params['price'];

        // $check = $this->productModel->buyProduct($productDetails, 1);
        // echo '<p>' .$check. '</p>';
        // // include(__DIR__ . "../../view/ProductList.php");

        echo '<p>BUYING... ' .$productDetails->size. '</p>';
    }

    public function addToCart($params) {
        $userId = $_SESSION['userId'];
        if(isset($params['prdetail_id'])) {
            $productDetails = new stdClass();
            $productDetails->id = $params['prdetail_id'];
            $productDetails->product_id = $params['pr_id'];
            $productDetails->size = $params['product-size'];
            $productDetails->price = 2000;
            $productDetails->quantity = $params['product-quanity'];
        
            $check = $this->cartModel->addToCart($productDetails, $userId);
        }
        $cart = $this->cartModel->getCartByUserId($userId);
        include(__DIR__ . "../../view/Cart.php");
    }
}
?>