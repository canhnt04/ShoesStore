<?php
require_once("Database.php");

class Product
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function searchProduct($keyword)
    {
        try {
            $keyword = $this->con->real_escape_string($keyword);
            $sql = "SELECT * FROM product WHERE name LIKE '%$keyword%'";
            $result = $this->con->query($sql);

            $productList = [];
            if ($result->num_rows > 0) {
                while ($product = $result->fetch_object()) {
                    $sqlDetails = "SELECT * FROM productdetail WHERE product_id = " . (int)$product->id;
                    $resultDetails = $this->con->query($sqlDetails);

                    $productDetails = [];
                    if ($resultDetails->num_rows > 0) {
                        while ($detailRow = $resultDetails->fetch_assoc()) {
                            $productDetails[] = $detailRow;
                        }
                    }

                    $product->productDetailsList = $productDetails;
                    $productList[] = $product;
                }
            }

            return $productList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function searchProductAdvanced($keyword = '', $categoryId = -1, $minPrice = 0, $maxPrice = PHP_INT_MAX)
    {
        try {
            // Escape keyword để tránh SQL Injection
            $keyword = $this->con->real_escape_string($keyword);

            // Tạo điều kiện SQL động
            $conditions = [];
            if (!empty($keyword)) {
                $conditions[] = "p.name LIKE '%$keyword%'";
            }

            if ($categoryId != -1) {
                $conditions[] = "p.category_id = " . (int)$categoryId;
            }

            $conditions[] = "pd.price BETWEEN " . (int)$minPrice . " AND " . (int)$maxPrice;

            // Kết hợp điều kiện
            $whereClause = implode(" AND ", $conditions);

            // Truy vấn JOIN product và productdetail
            $sql = "SELECT p.*, pd.id AS detail_id, pd.size, pd.color, pd.price, pd.quantity
                FROM product p
                JOIN productdetail pd ON p.id = pd.product_id
                WHERE $whereClause";

            $result = $this->con->query($sql);

            $productMap = [];

            // Gom dữ liệu theo sản phẩm, mỗi sản phẩm là 1 object có mảng chi tiết
            while ($row = $result->fetch_assoc()) {
                $productId = $row['id'];

                // Nếu chưa tồn tại trong map thì tạo mới
                if (!isset($productMap[$productId])) {
                    $product = new stdClass();
                    $product->id = $row['id'];
                    $product->name = $row['name'];
                    $product->image = $row['image']; // hoặc các trường khác
                    $product->category_id = $row['category_id'];
                    $product->productDetailsList = [];

                    $productMap[$productId] = $product;
                }

                // Thêm chi tiết sản phẩm
                $detail = [
                    'id' => $row['detail_id'],
                    'size' => $row['size'],
                    'color' => $row['color'],
                    'price' => $row['price'],
                    'quantity' => $row['quantity']
                ];

                $productMap[$productId]->productDetailsList[] = $detail;
            }

            // Trả về danh sách object sản phẩm
            return array_values($productMap);
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getTotalPage($categoryId = -1)
    {
        try {
            if ($categoryId == -1)
                $sql = "SELECT count(*) AS total FROM product";
            else
                $sql = "SELECT count(*) AS total FROM product WHERE category_id = $categoryId";
            $result = $this->con->query($sql);
            $row = $result->fetch_assoc();
            $totalRecord = $row['total'];

            $productPerPage = 6;
            $totalPage = ceil($totalRecord / $productPerPage);

            return $totalPage;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getAll($limit, $offset)
    {
        try {
            $sql = "SELECT * FROM product LIMIT $limit OFFSET $offset";
            $result = $this->con->query($sql);

            $productList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    // Gán dữ liệu product
                    $product = $row;

                    // Truy vấn chi tiết
                    $sqlDetails = "SELECT * FROM productdetail WHERE product_id = " . (int)$product->id;
                    $resultDetails = $this->con->query($sqlDetails);

                    $productDetails = [];
                    if ($resultDetails->num_rows > 0) {
                        while ($detailRow = $resultDetails->fetch_assoc()) {
                            $productDetails[] = $detailRow;
                        }
                    }

                    // Gán mảng chi tiết vào product
                    $product->productDetailsList = $productDetails;

                    // Thêm vào danh sách, mỗi phần tử là một object.
                    $productList[] = $product;
                }
            }

            return $productList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getById1($id)
    {
        try {
            $sql = "SELECT *
                FROM product p
                WHERE p.id = " . $id;
            $result = $this->con->query($sql);

            $product = null;
            if ($result->num_rows > 0) {
                $product = $result->fetch_object();

                $sqlDetails = "SELECT *
                            FROM  productdetail pd
                            WHERE pd.product_id = " . $id;
                $resultDetails = $this->con->query($sqlDetails);

                $product->productDetailsList = [];
                if ($resultDetails->num_rows > 0) {
                    while ($row = $resultDetails->fetch_object()) {
                        $product->productDetailsList[] = $row;
                    }
                }
            }

            return $product;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getById($id, $productDetailsId)
    {
        try {
            $sql = "SELECT *
                FROM product p
                WHERE p.id = " . $id;
            $result = $this->con->query($sql);

            $product = null;
            if ($result->num_rows > 0) {
                $product = $result->fetch_object();

                $sqlDetails = "SELECT *
                            FROM  productdetail pd
                            WHERE pd.product_id = " . $id .
                    " AND pd.id = " . $productDetailsId;
                $resultDetails = $this->con->query($sqlDetails);

                $product->productDetails = null;
                if ($resultDetails->num_rows > 0) {
                    while ($row = $resultDetails->fetch_object()) {
                        $product->productDetails = $row;
                    }
                }
            }

            return $product;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getSizeListByIdAndColor($productId, $productDetailsColor)
    {
        try {
            $sql = "SELECT pd.size, pd.id
                    FROM productdetail pd
                    WHERE pd.product_id = " . $productId .
                " AND pd.color = '" . $productDetailsColor . "'";

            $result = $this->con->query($sql);
            $sizeList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $sizeList[] = $row;
                }
            }
            return $sizeList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getColorListById($productId)
    {
        try {
            $sql = "SELECT pd.color, pd.id
                    FROM productdetail pd
                    WHERE pd.product_id = " . $productId .
                " GROUP BY pd.color";

            $result = $this->con->query($sql);
            $colorList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    $colorList[] = $row;
                }
            }
            return $colorList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getByCategory($category, $limit, $offset)
    {
        try {
            $sql = "SELECT *
                FROM product
                WHERE category_id = $category
                LIMIT $limit OFFSET $offset";
            $result = $this->con->query($sql);

            $productList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    // Gán dữ liệu product
                    $product = $row;

                    // Truy vấn chi tiết
                    $sqlDetails = "SELECT * FROM productdetail WHERE product_id = " . (int)$product->id;
                    $resultDetails = $this->con->query($sqlDetails);

                    $productDetails = [];
                    if ($resultDetails->num_rows > 0) {
                        while ($detailRow = $resultDetails->fetch_assoc()) {
                            $productDetails[] = $detailRow;
                        }
                    }

                    // Gán mảng chi tiết vào product
                    $product->productDetailsList = $productDetails;

                    // Thêm vào danh sách, mỗi phần tử là một object.
                    $productList[] = $product;
                }
            }
            return $productList;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function getProductDetailByID($productId, $productDetailsId)
    {
        try {
            $sql = "SELECT pd.size, pd.color, pd.price
                FROM productdetail pd
                WHERE pd.id = $productDetailsId AND pd.product_id = $productId";

            $result = $this->con->query($sql);

            $productDetails = null;
            if ($result->num_rows > 0) {
                $productDetails = $result->fetch_object();
            }

            return $productDetails;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }

    public function updateQuantity($productDetailsId, $quantity)
    {
        try {
            $sql = "UPDATE productdetail pd
                    SET pd.quantity = pd.quantity + $quantity
                    WHERE pd.id = $productDetailsId";
            $result = $this->con->query($sql);
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }
}
