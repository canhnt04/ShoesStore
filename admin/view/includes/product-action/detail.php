    <?php

    require_once __DIR__ . '/../../../controller/ProductController.php';
    require_once __DIR__ . '/../../../controller/ProductDetailController.php';

    $page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // Khai báo các controller
    $productController = new ProductController($connection);
    $productDetailController = new ProductDetailController($connection);


    $details = $productDetailController->getAllPaginated($limit, $offset);
    $totalCount = $productDetailController->countList();
    $totalPages = ceil($totalCount / $limit);

    // Lấy danh sách sản phẩm
    $products = $productController->getAllWithoutPagination();
    $productMap = [];
    foreach ($products as $product) {
        $productMap[$product->getId()] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'thumbnail' => $product->getThumbnail()
        ];
    }

    // echo '<pre>';
    // print_r($details);
    // echo '</pre>';

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
            <input type="text" class="input_search_account-action" placeholder="Tìm kiếm chi tiết" />
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
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($details as $detail): ?>
                <tr>
                    <td><?= htmlspecialchars($detail->getId()) ?></td>
                    <td><?= htmlspecialchars($productMap[$detail->getProductId()]['name']) ?>
                    </td>

                    <td><?= htmlspecialchars($detail->getQuantity()) ?></td>
                    <td><?= htmlspecialchars($detail->getSize()) ?></td>
                    <td><?= htmlspecialchars($detail->getColor()) ?></td>
                    <td><?= htmlspecialchars($detail->getMaterial()) ?></td>
                    <td><?= formatPriceVND($detail->getPrice()) ?></td>
                    <td> <?php if ($detail->getStatus() == 0): ?>
                            <span style="background-color: red; color: white; font-size: 14px; padding: 2px 4px; border-radius: 4px;">Đã ẩn</span>
                        <?php else: ?>
                            <span style="background-color: green; color: white; font-size: 14px; padding: 2px 4px; border-radius: 4px;">Đang bán</span>
                        <?php endif; ?>
                    </td>
                    <td class="table_col-action">
                        <span class="open_modal-edit-btn-detail"><i class="fa-solid fa-pen"></i></span>
                        <span class="seperator"></span>
                        <span class="open_modal-edit-btn"><i class="fa-solid fa-eye"></i></span>
                        <span class="seperator"></span>
                        <span>
                            <form method="POST" onsubmit="return confirmLock(this);">
                                <button type="submit" style="border: none; background: none; cursor: pointer;">
                                    <?php if ($detail->getStatus() == 1): ?>
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

    <!-- Form THÊM chi tiết sản phẩm mới -->
    <div class="modal" id="modal-create" style="display: none; opacity: 0;">
        <div class="modal-content phuc">
            <h2>Thêm chi tiết sản phẩm</h2>
            <span class="close">&times;</span>
            <form method="POST" class="form-create-user">
                <input type="hidden" name="action" value="create_detail_product">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($totalPages) ?>">
                <div class="form-group phuc">
                    <label for="product_id">Chọn sản phẩm muốn thêm chi tiết</label>
                    <select class="form-control" id="product_id" name="product_id">
                        <option value="">-- Chọn sản phẩm cần thêm chi tiết --</option>
                        <?php foreach ($productMap as $id => $data): ?>
                            <option value="<?= htmlspecialchars($id) ?>">
                                <?= htmlspecialchars($data['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="form-group phuc">
                    <label for="size">Size</label>
                    <select class="form-control" id="size" name="size" required>
                        <option value="">-- SIZE --</option>
                        <?php for ($i = 38; $i <= 50; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group phuc">
                    <label for="quantity">Số lượng</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>

                <div class="form-group phuc">
                    <label for="color">Màu sắc</label>
                    <input type="text" class="form-control" id="color" name="color" required>
                </div>

                <div class="form-group phuc">
                    <label for="material">Chất liệu</label>
                    <input type="text" class="form-control" id="material" name="material" required>
                </div>

                <div class="form-group phuc">
                    <label for="price">Giá bán</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>

                <button type="submit" class="btn">GỬI</button>
            </form>

        </div>
    </div>

    <!-- Form SỬA chi tiết sản phẩm -->
    <div class="modal" id="modal-edit" style="display: none; opacity: 0;">
        <div class="modal-content phuc">
            <h2>Sửa chi tiết sản phẩm</h2>
            <span class="close">&times;</span>
            <form method="POST" class="form-create-user">
                <input type="hidden" name="action" value="update_detail_product">
                <input type="hidden" name="pagination" value="<?= htmlspecialchars($totalPages) ?>">
                <input type="hidden" name="edit-id">

                <div class="form-group phuc">
                    <label for="product-name">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="product-name" id="product-name" value=<?= htmlspecialchars($productMap[$detail->getProductId()]['name']) ?> disabled>
                </div>


                <div class="form-group phuc">
                    <label for="size">Size</label>
                    <select class="form-control" id="edit-size" name="size" required>
                        <option value="">-- SIZE --</option>
                        <?php for ($i = 38; $i <= 50; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group phuc">
                    <label for="quantity">Số lượng</label>
                    <input type="number" class="form-control" id="edit-quantity" name="quantity" required>
                </div>

                <div class="form-group phuc">
                    <label for="color">Màu sắc</label>
                    <input type="text" class="form-control" id="edit-color" name="color" required>
                </div>

                <div class="form-group phuc">
                    <label for="material">Chất liệu</label>
                    <input type="text" class="form-control" id="edit-material" name="material" required>
                </div>

                <div class="form-group phuc">
                    <label for="price">Giá bán</label>
                    <input type="text" class="form-control" id="edit-price" name="price" required>
                </div>

                <button type="submit" class="btn">GỬI</button>
            </form>

        </div>
    </div>