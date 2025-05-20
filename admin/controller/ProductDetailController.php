<?php
include_once __DIR__ . '/../../config/init.php';
include_once __DIR__ . '/../model/Model/Model_ProductDetail.php';

class ProductDetailController
{
    private $model_product_detail;

    public function __construct($connection)
    {
        $this->model_product_detail = new Model_ProductDetail($connection);
    }
    // Hàm đếm số lượng chi tiết sản phẩm
    public function countList()
    {
        return $this->model_product_detail->countProductDetails();
    }

    // Hàm lấy danh sách chi tiết sản phẩm với phân trang
    public function getAllPaginated($limit, $offset)
    {
        return $this->model_product_detail->getAllProductDetails($limit, $offset);
    }

    // Hàm lấy danh sách chi tiết sản phẩm theo id
    public function getAllDetailsByProductId($productId)
    {
        return $this->model_product_detail->getAllDetailsByProductId($productId);
    }

    // Hàm tạo một chi tiết sản phẩm mới
    public function createProductDetail($data)
    {
        $product_id = $data['product_id'];
        $description = $data['description'] ?? "";
        $quantity = $data['quantity'];
        $size = $data['size'];
        $color = $data['color'];
        $material = $data['material'];
        $price = $data['price'];

        $this->model_product_detail->createProductDetail($product_id, $description, $quantity, $size, $color, $material, $price, 1);
    }

    // Hàm cập nhật một chi tiết sản phẩm
    public function updateProductDetail($data)
    {
        $id = $data['id'];
        $quantity = $data['quantity'];
        $size = $data['size'];
        $color = $data['color'];
        $material = $data['material'];
        $price = $data['price'];

        return $this->model_product_detail->updateProductDetail($size, $quantity, $color, $material, $price, $id);
    }

    // Ẩn hiện sản phẩm
    public function toggleDetailProduct($id, $dispatch)
    {
        if ($dispatch == 1) {
            return $this->model_product_detail->deleteProductDetail($id, 0);
        }
        return $this->model_product_detail->deleteProductDetail($id, 1);
    }
}
