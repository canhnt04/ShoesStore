    <?php
    // Cài đặt phân trang
    $page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $_SESSION['limit'] = $limit;
    $_SESSION['offset'] = $offset;

    // Lấy danh sách danh mục
    $_GET['action'] = 'get_all_categorys';
    include __DIR__ . '/../../../controller/CategoryController.php';
    $categorys = $_SESSION['categorys'] ?? [];
    $categoryMap = [];
    foreach ($categorys as $category) {
        if (method_exists($category, 'getId') && method_exists($category, 'getName')) {
            $categoryMap[$category->getId()] = $category->getName();
        }
    }

    // Lấy danh sách nhà cung cấp
    $_GET['action'] = 'get_all_suppliers';
    include __DIR__ . '/../../../controller/SupplierController.php';
    $suppliers = $_SESSION['suppliers'] ?? [];
    $supplierMap = [];
    foreach ($suppliers as $supplier) {
        if (method_exists($supplier, 'getId') && method_exists($supplier, 'getName')) {
            $supplierMap[$supplier->getId()] = $supplier->getName();
        }
    }

    // Lấy danh sách sản phẩm
    $_GET['action'] = 'get_product_without_pagination';
    include __DIR__ . '/../../../controller/ProductController.php';
    $products = $_SESSION['list_product'] ?? [];
    $productMap = [];
    foreach ($products as $product) {
        $productMap[$product->getId()] = [
            'name' => $product->getName(),
            'thumbnail' => $product->getThumbnail()
        ];
    }

    // echo '<pre>';
    // print_r($productMap);
    // echo '</pre>';


    // Gọi 2 hành động trong controller
    $_GET['action'] = 'count_list';
    include __DIR__ . '/../../../controller/ProductDetailController.php';

    $_GET['action'] = 'render_view';
    include __DIR__ . '/../../../controller/ProductDetailController.php';

    // Kiểm tra xem session có dữ liệu chưa
    $details = $_SESSION['detail_view'] ?? [];
    $totalCount = $_SESSION['count'] ?? 0;
    $totalPages = ceil($totalCount / $limit);

    function formatPriceVND($price)
    {
        // Ép kiểu về float
        $price = (float) $price;

        // Định dạng: không hiển thị số lẻ, dùng dấu . ngăn cách hàng nghìn
        $formattedPrice = number_format($price, 0, ',', '.');

        // Thêm đơn vị VNĐ
        return $formattedPrice . ' ₫';
    }


    ?>

    <div class="account-action">
        <div class="search-box">
            <input type="text" class="input_search_account-action" placeholder="Tìm kiếm sản phẩm" />
        </div>
        <div class="group-button_account-action">
            <button class="button_account-action blue" id="open_modal-create-btn">
                <i class="fa-solid fa-plus"></i>
                <span>Thêm</span>
            </button>
        </div>
    </div>

    <?php if (!empty($details)): ?>
        <table border='1'>
            <tr>
                <th>ID chi tiết</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Size</th>
                <th>Màu sắc</th>
                <th>Chất liệu</th>
                <th>Thương hiệu</th>
                <th>Giá</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($details as $detail): ?>
                <tr>
                    <td><?= htmlspecialchars($detail->getProductId()) ?></td>
                    <td><?= htmlspecialchars($productMap[$detail->getProductId()]['name']) ?>
                    </td>

                    <td><?= htmlspecialchars($detail->getQuantity()) ?></td>
                    <td><?= htmlspecialchars($detail->getSize()) ?></td>
                    <td><?= htmlspecialchars($detail->getColor()) ?></td>
                    <td><?= htmlspecialchars($detail->getMaterial()) ?></td>
                    <td><?= htmlspecialchars($detail->getBrand()) ?></td>
                    <td><?= formatPriceVND($detail->getPrice()) ?></td>
                    <td class="table_col-action">
                        <span class="open_modal-edit-btn"><i class="fa-solid fa-pen"></i></span>
                        <span class="seperator"></span>
                        <span class="open_modal-edit-btn"><i class="fa-solid fa-eye"></i></span>
                        <span class="seperator"></span>
                        <span><i class="fa-solid fa-lock"></i>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Phân trang -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="index.php?page=product_manager&tab=detail&pagination=<?= $page - 1 ?>">« Trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?page=product_manager&tab=detail&pagination=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="index.php?page=product_manager&tab=detail&pagination=<?= $page + 1 ?>">Tiếp »</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Không có chi tiết sản phẩm nào.</p>
    <?php endif; ?>

    <!-- Form thêm chi tiết sản phẩm mới -->
    <div class="modal" id="modal-create">
        <div class="modal-content">
            <h2>Thêm chi tiết sản phẩm</h2>
            <span class="close">&times;</span>
            <form action="/DoAn/ShoesStore/admin/controller/ProductController.php" method="POST" enctype="multipart/form-data" class="form-create-user">
                <input type="hidden" name="action" value="create_product">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($totalPages) ?>">
                <div class="form-group">
                    <label for="name">Chọn sản phẩm muốn thêm chi tiết</label>
                    <select class="form-control" id="product_name" name="product_name">
                        <option value="">-- Chọn sản phẩm cần thêm chi tiết --</option>
                        <?php foreach ($productMap as $id => $data): ?>
                            <option value="<?= htmlspecialchars($id) ?>">
                                <?= htmlspecialchars($data['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="size">Danh mục</label>
                    <select class="form-control" id="size" name="size" required>
                        <option value="">-- SIZE --</option>
                        <?php for ($i = 38; $i <= 50; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="supplier_id">Nhà cung cấp</label>
                    <select class="form-control" id="supplier_id" name="supplier_id" required>
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($supplierMap as $id => $name): ?>
                            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">-- Trạng thái --</option>

                        <option value="0">Mở bán</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <button type="submit" class="btn">GỬI</button>
            </form>

        </div>
    </div>