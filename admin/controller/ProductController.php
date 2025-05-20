<?php
include_once __DIR__ . '/../../config/init.php';
include_once __DIR__ . '/../model/Model/Model_Product.php';

class ProductController
{
    private $model_product;
    private $model_product_detail;
    private $model_product_category;
    private $model_product_supplier;

    public function __construct($connection)
    {
        $this->model_product = new Model_Product($connection);
    }

    public function countList(): int
    {
        return $this->model_product->countProducts();
    }

    public function getAllWithoutPagination()
    {
        return $this->model_product->getAllProductsWithoutPagination();
    }

    public function getAllPaginated(int $limit, int $offset): array
    {
        return $this->model_product->getAllProducts($limit, $offset);
    }

    public function getProductById($productId)
    {
        return $this->model_product->getProductById($productId);
    }

    public function create($data)
    {
        $name = $data['name'] ?? null;
        $category_id = $data['category_id'] ?? null;
        $supplier_id = $data['supplier_id'] ?? null;
        $brand = $data['brand'] ?? "";
        $status = $data['status'] ?? 1;

        // Xử lý hình ảnh upload
        $thumbnail = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $thumbnail = basename($_FILES['thumbnail']['name']);
            $uploadPath = $uploadDir . $thumbnail;

            // Tạo thư mục nếu chưa có
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath);
        }

        // Gọi model để lưu sản phẩm
        return $this->model_product->createProduct($name, $thumbnail, $category_id, $supplier_id, $brand, $status);
    }

    public function update($data)
    {
        $id = $data['id'];
        $name = $data['name'];
        $category_id = $data['category_id'];
        $oldThumbnail = $data['old_thumbnail'] ?? '';
        $thumbnail = $oldThumbnail; // mặc định giữ ảnh cũ

        // Kiểm tra nếu người dùng upload ảnh mới
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $newThumbnail = uniqid('product_', true) . '.' . $ext;
            $uploadPath = $uploadDir . $newThumbnail;

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                $thumbnail = $newThumbnail;

                if ($oldThumbnail && $oldThumbnail !== 'no_image.png') {
                    $oldPath = $uploadDir . $oldThumbnail;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            } else {
                error_log("Không thể upload ảnh vào: $uploadPath");
            }
        }

        // Gọi model để cập nhật
        $this->model_product->updateProduct($id, $name, $thumbnail, $category_id);
    }

    public function toggleStatus(int $id, bool $dispatch)
    {
        if ($dispatch == 1) {
            return $this->model_product->deleteProduct($id, 0);
        }
        return $this->model_product->deleteProduct($id, 1);
    }

    private function handleUpload(array $file, string $oldFile = ''): string
    {
        $uploadDir = __DIR__ . '/../uploads/';
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_', true) . '.' . $ext;
        $uploadPath = $uploadDir . $filename;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            if ($oldFile && file_exists($uploadDir . $oldFile)) {
                unlink($uploadDir . $oldFile);
            }
            return $filename;
        }

        return $oldFile ?: 'no_image.png';
    }
}
