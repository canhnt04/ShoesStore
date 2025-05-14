    <?php
    // Cài đặt phân trang
    $page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $_SESSION['limit'] = $limit;
    $_SESSION['offset'] = $offset;

    $_GET['action'] = 'get_all_categorys';
    include __DIR__ . '/../../../controller/CategoryController.php';
    $categorys = $_SESSION['categorys'] ?? [];
    $categoryMap = [];
    foreach ($categorys as $category) {
        if (method_exists($category, 'getId') && method_exists($category, 'getName')) {
            $categoryMap[$category->getId()] = $category->getName();
        }
    }


    $_GET['action'] = 'get_all_suppliers';
    include __DIR__ . '/../../../controller/SupplierController.php';
    $suppliers = $_SESSION['suppliers'] ?? [];
    $supplierMap = [];
    foreach ($suppliers as $supplier) {
        if (method_exists($supplier, 'getId') && method_exists($supplier, 'getName')) {
            $supplierMap[$supplier->getId()] = $supplier->getName();
        }
    }

    // Gọi 2 hành động trong controller
    $_GET['action'] = 'count_list';
    include __DIR__ . '/../../../controller/ProductController.php';

    $_GET['action'] = 'render_view';
    include __DIR__ . '/../../../controller/ProductController.php';

    // Kiểm tra xem session có dữ liệu chưa
    $products = $_SESSION['product_view'] ?? [];
    $totalCount = $_SESSION['count'] ?? 0;
    $totalPages = ceil($totalCount / $limit);

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

    <?php if (!empty($products)): ?>
        <table border='1'>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Danh mục</th>
                <th>Nhà cung cấp</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product->getId()) ?></td>
                    <td><?= htmlspecialchars($product->getName()) ?></td>
                    <td>
                        <img src="/DoAn/ShoesStore/admin/uploads/<?= htmlspecialchars($product->getThumbnail()) ?>"
                            alt="Hình ảnh sản phẩm"
                            width="80"
                            onerror="this.onerror=null;this.src='/DoAn/ShoesStore/public/assets/images/no_image.png';">
                    </td>
                    <td><?= htmlspecialchars($categoryMap[$product->getCategoryId()] ?? 'Không rõ') ?></td>
                    <td><?= htmlspecialchars($supplierMap[$product->getSupplierId()] ?? 'Không rõ') ?></td>
                    <td><?= htmlspecialchars($product->getStatus() == 0 ? "Đã ẩn" : "Đang bán")  ?></td>
                    <td class="table_col-action">
                        <span class="open_modal-edit-btn"><i class="fa-solid fa-pen"></i></span>
                        <span class="seperator"></span>
                        <span>
                            <form action="/DoAn/ShoesStore/admin/controller/ProductController.php" method="POST">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="id" value="<?= $product->getId() ?>">
                                <input type="hidden" name="pagination" value="<?= htmlspecialchars($_GET['pagination'] ?? 1) ?>">
                                <input type="hidden" name="dispatch" value="<?= $product->getStatus() == 1 ? 'lock' : 'unlock' ?>">
                                <button type="submit" style="border: none; background: none; cursor: pointer;" title="<?= $product->getStatus() == 1 ? 'Ẩn sản phẩm' : 'Đã ẩn' ?>">
                                    <?php if ($product->getStatus() == 1): ?>
                                        <i class="fa-solid fa-lock"></i>
                                    <?php else: ?>
                                        <i class="fa-solid fa-lock-open"></i>
                                    <?php endif; ?>
                                </button>
                            </form>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Phân trang -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="index.php?page=product_manager&tab=product&pagination=<?= $page - 1 ?>">« Trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?page=product_manager&tab=product&pagination=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="index.php?page=product_manager&tab=product&pagination=<?= $page + 1 ?>">Tiếp »</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Không có sản phẩm nào.</p>
    <?php endif; ?>

    <!-- Form thêm sản phẩm mới -->
    <div class="modal" id="modal-create">
        <div class="modal-content">
            <h2>Thêm sản phẩm</h2>
            <span class="close">&times;</span>
            <form action="/DoAn/ShoesStore/admin/controller/ProductController.php" method="POST" enctype="multipart/form-data" class="form-create-user">
                <input type="hidden" name="action" value="create_product">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($totalPages) ?>">
                <div class="form-group">
                    <label for="name">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Tải hình ảnh sản phẩm</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categoryMap as $id => $name): ?>
                            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
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
    <!-- Modal cập nhật sản phẩm -->
    <div class="modal" id="modal-edit">z
        <div class="modal-content">
            <h2>Cập nhật sản phẩm</h2>
            <span class="close" id="closeEdit">&times;</span>
            <form action="/DoAn/ShoesStore/admin/controller/ProductController.php" method="POST" enctype="multipart/form-data" class="form-edit-product">
                <input type="hidden" name="action" value="update_product">
                <input type="hidden" id="edit_id" name="id">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($_GET['pagination'] ?? 1) ?>">

                <div class="form-group">
                    <label for="edit_name">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="edit_thumbnail">Cập nhật hình ảnh</label>

                    <input type="file" class="form-control" id="edit_thumbnail" name="thumbnail" value="">
                    <input type="hidden" name="old_thumbnail" id="old_thumbnail" value="">

                    <img id="current_thumbnail" src="" alt="Current Thumbnail" width="300" style="margin-top: 10px;">
                </div>

                <button type="submit" class="btn" style="width: 100%;">Cập nhật</button>
            </form>
        </div>
    </div>