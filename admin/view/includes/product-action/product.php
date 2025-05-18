    <?php
    // Cài đặt phân trang
    include __DIR__ . '/../../../controller/ProductController.php';
    include __DIR__ . '/../../../controller/ProductDetailController.php';
    include __DIR__ . '/../../../controller/CategoryController.php';
    include __DIR__ . '/../../../controller/SupplierController.php';

    $page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // Khai báo các controller
    $productController = new ProductController($connection);

    $categoryController = new CategoryController($connection);
    $supplierController = new SupplierController($connection);

    $products = $productController->getAllPaginated($limit, $offset);
    // echo '<pre>';
    // print_r($products);
    // echo '</pre>';

    $totalCount = $productController->countList();
    $totalPages = ceil($totalCount / $limit);

    // Lấy danh sách danh mục
    $categorys = $categoryController->getAllCategories();
    $categoryMap = [];
    foreach ($categorys as $category) {
        $categoryMap[$category->getId()] = [
            'id' => (int)$category->getId(),
            'name' => $category->getName()
        ];
    }

    // echo '<pre>';
    // print_r($categoryMap);
    // echo '</pre>';


    // Lấy danh sách nhà cung cấp
    $suppliers = $supplierController->getAllSuppliers();
    $supplierMap = [];
    foreach ($suppliers as $supplier) {
        $supplierMap[$supplier->getId()] = [
            'id' => $supplier->getId(),
            'name' => $supplier->getName()
        ];
    }

    // echo '<pre>';
    // print_r($supplierMap);
    // echo '</pre>';

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
                <th>Thương hiệu</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr data-product-id="<?= $product->getId() ?>"
                    data-category-id="<?= ($categoryMap[$product->getCategoryId()]['id']) ?>">
                    <td><?= htmlspecialchars($product->getId()) ?></td>
                    <td><?= htmlspecialchars($product->getName()) ?></td>
                    <td>
                        <img src="/ShoesStore/admin/uploads/<?= htmlspecialchars($product->getThumbnail()) ?>"
                            alt="Hình ảnh sản phẩm"
                            width="70"
                            onerror="this.onerror=null;this.src='/DoAn/ShoesStore/public/assets/images/no_image.png';">
                    </td>
                    <td>
                        <?= ($categoryMap[$product->getCategoryId()]['name'] ?? 'Không rõ') ?>
                    </td>
                    <td><?= ($supplierMap[$product->getSupplierId()]['name'] ?? 'Không rõ') ?></td>
                    <td><?= htmlspecialchars($product->getBrand()) ?></td>
                    <td> <?php if ($product->getStatus() == 0): ?>
                            <span style="background-color: red; color: white; font-size: 14px; padding: 2px 6px; border-radius: 4px;">Đã ẩn</span>
                        <?php else: ?>
                            <span style="background-color: green; color: white; font-size: 14px; padding: 2px 6px; border-radius: 4px;">Đang bán</span>
                        <?php endif; ?>
                    </td>
                    <td class="table_col-action">
                        <span class="open_modal-edit-btn"><i class="fa-solid fa-pen"></i></span>
                        <span class="seperator"></span>
                        <span class="open_modal-detail-btn"><i class="fa-solid fa-eye"></i></span>
                        <span class="seperator"></span>
                        <span>
                            <form method="POST" onsubmit="return confirmLock(this);">

                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="id" value="<?= $product->getId() ?>">
                                <input type="hidden" name="pagination" value="<?= htmlspecialchars($_GET['pagination'] ?? 1) ?>">
                                <input type="hidden" name="dispatch" value="<?= $product->getStatus() ?>">
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
    <div class="modal" id="modal-create" style="display: none; opacity: 0;">
        <div class="modal-content phuc">
            <h2>Thêm sản phẩm</h2>
            <span class="close">&times;</span>
            <form method="POST" enctype="multipart/form-data" class="form-create-user">
                <input type="hidden" name="action" value="create_product">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($totalPages) ?>">


                <div class="form-group phuc">
                    <label for="name">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group phuc">
                    <label for="thumbnail">Tải hình ảnh sản phẩm</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" required>
                    <img id="preview_thumbnail" src="#" alt="Preview" style="display: none; margin-top: 10px;" width="150">
                </div>

                <div class="form-group phuc">
                    <label for="category_id">Danh mục</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categoryMap as $id => $data): ?>
                            <option value="<?= htmlspecialchars($data['id']) ?>"><?= htmlspecialchars($data['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group phuc">
                    <label for="supplier_id">Nhà cung cấp</label>
                    <select class="form-control" id="supplier_id" name="supplier_id" required>
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($supplierMap as $id => $data): ?>
                            <option value="<?= htmlspecialchars($data['id']) ?>"><?= htmlspecialchars($data['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group phuc">
                    <label for="brand">Thương hiệu</label>
                    <input type="text" class="form-control" id="brand" name="brand" required>
                </div>

                <div class="form-group phuc">
                    <label for="status">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">-- Trạng thái --</option>

                        <option value="1">Mở bán</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <button type="submit" class="btn">GỬI</button>
            </form>

        </div>
    </div>
    <!-- Modal cập nhật sản phẩm -->
    <div class="modal" id="modal-edit" style="display: none; opacity: 0;">
        <div class="modal-content">
            <h2>Cập nhật sản phẩm</h2>
            <span class="close" id="closeEdit">&times;</span>
            <form method="POST" enctype="multipart/form-data" class="form-edit-product">
                <input type="hidden" name="action" value="update_product">
                <input type="hidden" id="edit_id" name="id">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($_GET['pagination'] ?? 1) ?>">

                <div class="form-group phuc">
                    <label for="edit_name">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                </div>

                <div class="form-group phuc">
                    <label for="edit-category">Danh mục</label>
                    <select class="form-control" id="edit-category" name="category_id" required>
                        <?php foreach ($categoryMap as $id => $data): ?>
                            <option value="<?= (string) $data['id'] ?>"><?= htmlspecialchars($data['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group phuc">
                    <label for="edit_thumbnail">Cập nhật hình ảnh</label>

                    <input type="file" class="form-control" id="edit_thumbnail" name="thumbnail" value="">
                    <input type="hidden" name="old_thumbnail" id="old_thumbnail" value="">

                    <img id="current_thumbnail" src="" alt="Current Thumbnail" width="300" style="margin-top: 10px;">
                </div>

                <button type="submit" class="btn" style="width: 100%;">Cập nhật</button>
            </form>
        </div>
    </div>

    <!-- Modal xem chi tiết sản phẩm -->
    <div id="order-modal" style="display:none;" class="modal">
        <div class="modal-content">
            <h2>Chi tiết sản phẩm</h2>
            <span class="close close-detail-modal">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById("thumbnail").addEventListener("change", function(event) {
            const input = event.target;
            const preview = document.getElementById("preview_thumbnail");

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "#";
                preview.style.display = "none";
            }
        });

        function confirmLock(form) {
            const isLocked = form.querySelector('input[name="dispatch"]').value === 'lock';
            const message = isLocked ? "Bạn có chắc chắn muốn ẩn sản phẩm này?" : "Bạn có chắc chắn muốn mở bán lại sản phẩm này?";
            return confirm(message);
        }

        // Mở modal xem chi tiết sản phẩm
        document.querySelectorAll(".open_modal-detail-btn").forEach((btn) => {
            btn.addEventListener("click", function() {
                const productId = this.closest("tr").dataset.productId;

                $.ajax({
                    url: "ajax-handler/order/get_product_detail.php",
                    method: "POST",
                    data: {
                        productId: productId
                    },
                    success: function(html) {
                        $('#modal-body').html(html);
                        $('#order-modal').fadeIn(50, 'linear')
                    },
                    error: function() {
                        $('#modal-body').html('<p>Lỗi khi tải dữ liệu.</p>');
                        $('#order-modal').fadeIn()
                    }
                });
            });
        });

        // Đóng modal xem chi tiết
        document.querySelector('.close-detail-modal').addEventListener('click', function() {
            $('#order-modal').fadeOut(100, function() {
                $('#modal-body').html(''); // Xóa nội dung khi đóng để tránh lỗi hoặc trùng ID
            });
        });
    </script>