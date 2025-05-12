<?php
include_once __DIR__ . '/../../../controller/UserController.php';

$page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$limit = 7; // Số lượng phần tử mỗi trang
$offset = ($page - 1) * $limit;

$userController = new UserController();

// Lấy danh sách user và tổng số user
$users = $userController->listUsers($limit, $offset);
$totalUsers = $userController->countUsers();
$totalPages = ceil($totalUsers / $limit);

?>

<div class="account-action">
    <div class="search-box">
        <input type="text" class="input_search_account-action" placeholder="Tìm kiếm tài khoản" />
    </div>
    <div class="group-button_account-action">
        <button class="button_account-action blue" id="add_user-btn">
            <i class="fa-solid fa-user-plus"></i>
            <span>Thêm</span>
        </button>
    </div>
</div>

<?php if (!empty($users)): ?>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user->getId()) ?></td>
                <td><?= htmlspecialchars($user->getUsername()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td><?= htmlspecialchars($user->getRoleId()) ?></td>
                <td><?= htmlspecialchars($user->getStatus()) ?></td>
                <td class="table_col-action">
                    <span><i class="fa-solid fa-pen"></i></span>
                    <span class="seperator"></span>
                    <span><i class="fa-solid fa-eye"></i></span>
                    <span class="seperator"></span>
                    <span><i class="fa-solid fa-lock"></i></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=employee_manager&tab=account&pagination=<?= $page - 1 ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?page=employee_manager&tab=account&pagination=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?page=employee_manager&tab=account&pagination=<?= $page + 1 ?>">Tiếp »</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <p>Không có người dùng nào.</p>
<?php endif; ?>

<div class="modal" id="myModal">
    <div class="modal-content">
        <h2>Thêm tài khoản nhân viên</h2>
        <span class="close">&times;</span>
        <form action="/DoAn/ShoesStore/admin/controller/User_Controller/create_user.php " method="POST" class="form-create-user">
            <input type="hidden" name="action" value="create_user">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="role_id">Role ID</label>
                <input type="number" class="form-control" id="role_id" name="role_id" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn">GỬI</button>
        </form>
    </div>
</div>
