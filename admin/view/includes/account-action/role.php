<?php
include_once __DIR__ . '/../../../controller/RoleController.php';

$page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$limit = 5; // Số lượng phần tử mỗi trang
$offset = ($page - 1) * $limit;

$roleController = new RoleController($connection);

// Lấy danh sách user và tổng số user
$roles = $roleController->getAllRoles($limit, $offset);
$totalRoles = $roleController->countList();
$totalPages = ceil($totalRoles / $limit);
?>

<div class="account-action">
    <div class="search-box">
        <input type="text" class="input_search_account-action" placeholder="Tìm kiếm tài khoản" />
    </div>
    <div class="group-button_account-action">
        <button class="button_account-action blue" id="open_modal-add-btn">
            <span>Thêm</span>
        </button>
    </div>
</div>

<?php if (!empty($roles)): ?>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Tên vai trò</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($roles as $role): ?>
            <tr data-role-id="<?= $role->getId() ?>">
                <td><?= htmlspecialchars($role->getId()) ?></td>
                <td><?= htmlspecialchars($role->getName()) ?></td>
                <td class="table_col-action">
                    <span class="open_modal-update-btn"><i class="fa-solid fa-pen"></i></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=user_manager&tab=role&pagination=<?= $page - 1 ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?page=user_manager&tab=role&pagination=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?page=user_manager&tab=role&pagination=<?= $page + 1 ?>">Tiếp »</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <p>Không có vai trò nào.</p>
<?php endif; ?>

<div id="order-modal" style="display:none;" class="modal">
    <div class="modal-content">
        <span class="close close-detail-modal">&times;</span>
        <div id="modal-body"></div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Mở modal thêm role
    document.querySelectorAll("#open_modal-add-btn").forEach((btn) => {
        btn.addEventListener("click", function() {

            $.ajax({
                url: "ajax-handler/role/add_role.php",
                method: "POST",
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

    // Mở modal chỉnh sửa role
    document.querySelectorAll(".open_modal-update-btn").forEach((btn) => {
        btn.addEventListener("click", function() {
            const roleId = this.closest("tr").dataset.roleId;

            $.ajax({
                url: "ajax-handler/role/update_role.php",
                method: "POST",
                data: {
                    roleId: roleId
                },
                success: function(html) {
                    $('#modal-body').html(html);
                    $('#order-modal').fadeIn(50, 'linear');
                },
                error: function() {
                    $('#modal-body').html('<p>Lỗi khi tải dữ liệu.</p>');
                    $('#order-modal').fadeIn();
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