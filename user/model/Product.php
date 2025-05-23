<?php
require_once __DIR__ . "/../../config/init.php";

class Product
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->getConnection();
    }

    public function searchProduct($keyword, $brand, $price, $limit, $offset)
    {
        try {
            $sql = "SELECT DISTINCT p.*, pd.color
                FROM product p 
                JOIN productdetail pd ON p.id = pd.product_id 
                WHERE p.name LIKE ?";

            $params = ['%' . $keyword . '%'];
            $types = "s";

            if (!empty($brand)) {
                $sql .= " AND p.brand = ?";
                $params[] = $brand;
                $types .= "s";
            }

            if (!empty($price)) {
                if ($price === "low") {
                    $sql .= " AND pd.price < 200000";
                } elseif ($price === "medium") {
                    $sql .= " AND pd.price BETWEEN 200000 AND 500000";
                } elseif ($price === "high") {
                    $sql .= " AND pd.price > 500000";
                }
            }

            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= "ii";

            $stmt = $this->con->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $products = [];
            while ($row = $result->fetch_assoc()) {
                // Lấy danh sách chi tiết sản phẩm cho mỗi sản phẩm
                $productId = $row["id"];
                $stmtDetail = $this->con->prepare("SELECT * FROM productdetail WHERE product_id = ?");
                $stmtDetail->bind_param("i", $productId);
                $stmtDetail->execute();
                $resultDetail = $stmtDetail->get_result();

                $productDetails = [];
                while ($detail = $resultDetail->fetch_assoc()) {
                    $productDetails[] = $detail;
                }

                // Gán danh sách chi tiết vào sản phẩm
                $row["productDetailsList"] = $productDetails;

                $products[] = $row;
            }

            return $products;
        } catch (Exception $ex) {
            throw new Exception("SQL Error: " . $ex->getMessage());
        }
    }
    public function getTotalPageSearch($keyword, $brand, $price)
    {
        try {
            $sql = "SELECT COUNT(DISTINCT p.id) as total
                FROM product p
                JOIN productdetail pd ON p.id = pd.product_id
                WHERE 1=1";
            $params = [];
            $types = "";

            if (!empty($keyword)) {
                $sql .= " AND p.name LIKE ?";
                $params[] = "%$keyword%";
                $types .= "s";
            }

            if (!empty($brand)) {
                $sql .= " AND p.brand = ?";
                $params[] = $brand;
                $types .= "s";
            }

            if (!empty($price)) {
                if ($price === "low") {
                    $sql .= " AND pd.price < 200000";
                } elseif ($price === "medium") {
                    $sql .= " AND pd.price BETWEEN 200000 AND 500000";
                } elseif ($price === "high") {
                    $sql .= " AND pd.price > 500000";
                }
            }

            $stmt = $this->con->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return ceil($row['total'] / 6);
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
            $sql = "SELECT * FROM product WHERE status = 1 LIMIT $limit OFFSET $offset";
            $result = $this->con->query($sql);

            $productList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Gán dữ liệu product
                    $product = $row;

                    // Truy vấn chi tiết
                    $sqlDetails = "SELECT * FROM productdetail WHERE product_id = " . (int)$product['id'];
                    $resultDetails = $this->con->query($sqlDetails);

                    $productDetails = [];
                    if ($resultDetails->num_rows > 0) {
                        while ($detailRow = $resultDetails->fetch_assoc()) {
                            $productDetails[] = $detailRow;
                        }
                    }

                    // Gán mảng chi tiết vào product
                    $product['productDetailsList'] = $productDetails;

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
                $product = $result->fetch_assoc();

                $sqlDetails = "SELECT *
                            FROM  productdetail pd
                            WHERE pd.product_id = " . $id;
                $resultDetails = $this->con->query($sqlDetails);

                $product->productDetailsList = [];
                if ($resultDetails->num_rows > 0) {
                    while ($row = $resultDetails->fetch_assoc()) {
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
                $product = $result->fetch_assoc();

                $sqlDetails = "SELECT *
                            FROM  productdetail pd
                            WHERE pd.product_id = " . $id .
                    " AND pd.id = " . $productDetailsId;
                $resultDetails = $this->con->query($sqlDetails);

                $product["productDetails"] = [];
                if ($resultDetails->num_rows > 0) {
                    while ($row = $resultDetails->fetch_assoc()) {
                        $product["productDetails"] = $row;
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
                while ($row = $result->fetch_assoc()) {
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
                while ($row = $result->fetch_assoc()) {
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
                while ($row = $result->fetch_assoc()) {
                    // Gán dữ liệu product
                    $product = $row;

                    // Truy vấn chi tiết
                    $sqlDetails = "SELECT * FROM productdetail WHERE product_id = " . (int)$product["id"];
                    $resultDetails = $this->con->query($sqlDetails);

                    $productDetails = [];
                    if ($resultDetails->num_rows > 0) {
                        while ($detailRow = $resultDetails->fetch_assoc()) {
                            $productDetails[] = $detailRow;
                        }
                    }

                    // Gán mảng chi tiết vào product
                    $product["productDetailsList"] = $productDetails;

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
                $productDetails = $result->fetch_assoc();
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
