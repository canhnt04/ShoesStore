<?php
/* Controller PagesController sẽ được gọi bởi router nếu đúng định tuyến uri */

require_once __DIR__ . "/../controller/BaseController.php";
require_once __DIR__ . "/../model/Product.php";

class ProductController extends BaseController
{
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function showList()
    {
        $productList = $this->productModel->getAll();
        include(__DIR__ . "../../view/productList.php");
    }

    public function showById($id) {
        $product = $this->productModel->getById($id);
        // include(__DIR__ . "../../view/productDetail.php");
        if ($product) {
            echo "ID: " .$product['id']. " Name " .$product['name'];
        }
    }
}
?>