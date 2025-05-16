    <?php
    // Cài đặt phân trang
    $page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $_SESSION['limit'] = $limit;
    $_SESSION['offset'] = $offset;


    // Lấy danh sách sản phẩm để tiện cho việc lấy hình ảnh
    $_GET['action'] = 'get_product_without_pagination';
    include __DIR__ . '/../../../controller/ProductController.php';
    $products = $_SESSION['list_product'] ?? [];
    $productMap = [];
    foreach ($products as $product) {
        $productMap[$product->getId()] = $product->getThumbnail();
    }

    // Gọi 2 hành động trong controller
    $_GET['action'] = 'count_list';
    include __DIR__ . '/../../../controller/ProductDetailController.php';

    $_GET['action'] = 'render_view';
    include __DIR__ . '/../../../controller/ProductDetailController.php';

    // Kiểm tra xem session có dữ liệu chưa
    $details = $_SESSION['detail_view'] ?? [];
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

    <?php if (!empty($details)): ?>
        <table border='1'>
            <tr>
                <th>ID</th>
                <th>Id sản phẩm</th>
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
                    <td><?= htmlspecialchars($detail->getId()) ?></td>
                    <td>
                        <img src="/DoAn/ShoesStore/admin/uploads/<?= htmlspecialchars($productMap[$detail->getProductId()]) ?>"
                            alt="Hình ảnh sản phẩm"
                            width="80"
                            onerror="this.onerror=null;this.src='/DoAn/ShoesStore/public/assets/images/no_image.png';">
                    </td>
                    <td><?= htmlspecialchars($detail->getQuantity()) ?></td>
                    <td><?= htmlspecialchars($detail->getSize()) ?></td>
                    <td><?= htmlspecialchars($detail->getColor()) ?></td>
                    <td><?= htmlspecialchars($detail->getMaterial()) ?></td>
                    <td><?= htmlspecialchars($detail->getBrand()) ?></td>
                    <td><?= htmlspecialchars($detail->getPrice()) ?></td>
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