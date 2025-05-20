<?php
include_once __DIR__ . '/../../../controller/UserController.php';

$page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$limit = 7; // Số lượng phần tử mỗi trang
$offset = ($page - 1) * $limit;

$userController = new UserController($connection);

// Lấy danh sách user và tổng số user
$users = $userController->listCustomerUsers($limit, $offset);
$totalUsers = $userController->countCustomerUsers();
$totalPages = ceil($totalUsers / $limit);


?>

<?php if (!empty($users)): ?>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr data-account-id="<?= htmlspecialchars($user->getId()) ?>" data-status="<?= htmlspecialchars($user->getStatus()) ?>">
                <td><?= htmlspecialchars($user->getId()) ?></td>
                <td><?= htmlspecialchars($user->getUsername()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td><?php if ($user->getStatus() == 0): ?>
                        <span style="background-color: red; color: white; font-size: 14px; padding: 2px 6px; border-radius: 4px;">Bị khóa</span>
                    <?php else: ?>
                        <span style="background-color: green; color: white; font-size: 14px; padding: 2px 6px; border-radius: 4px;">Hoạt động</span>
                    <?php endif; ?>
                </td>
                <td class="table_col-action">
                    <span class="btn-lock">
                        <?php if ($user->getStatus() == 1): ?>
                            <i class="fa-solid fa-lock"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-lock-open"></i>
                        <?php endif; ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=user_manager&tab=account&pagination=<?= $page - 1 ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?page=user_manager&tab=account&pagination=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?page=user_manager&tab=account&pagination=<?= $page + 1 ?>">Tiếp »</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <p>Không có người dùng nào.</p>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.querySelectorAll(".btn-lock").forEach((btn) => {
        btn.addEventListener("click", function() {
            const accountId = this.closest("tr").dataset.accountId;
            const status = this.closest("tr").dataset.status;

            $.ajax({
                url: "ajax-handler/account/delete.php",
                method: "POST",
                data: {
                    accountId: accountId,
                    status: status
                },
                success: function(response) {
                    alert(response.message); // hiện thông báo từ server
                    if (response.status === 'success') {
                        location.reload(); // làm mới lại bảng nếu cần
                    }
                },
                error: function() {
                    alert('Lỗi khi gửi yêu cầu đến server.');
                }
            });
        });
    });
</script>