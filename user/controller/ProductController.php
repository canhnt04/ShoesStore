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
        echo'hello';
        $productList = $this->productModel->getAll();
        echo "<h2>Danh sách sản phẩm</h2>";
        echo "<ul>";
        foreach ($productList as $product) {
            echo "<li>{$product['name']} - ID: " . number_format($product['id']) . "</li>";
        }
        echo "</ul>";
    }
}
?>