<?php
include_once __DIR__ . '/../../../controller/UserController.php';

$userController = new UserController();
$users = $userController->listUsers(); // Lấy danh sách trực tiếp từ Controller

?>
<div class="account-action">
    <div class="search-box">
        <input type="text" class="input_search_account-action" placeholder="Tìm kiếm tài khoản" />
    </div>
    <div class="group-button_account-action">
        <button class="button_account-action blue">
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
                <td class="table_col-action"><span><i class="fa-solid fa-pen"></i></span>
                    <span><i class="fa-solid fa-eye"></i></span>
                    <span><i class="fa-solid fa-lock"></i></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Không có người dùng nào.</p>
<?php endif; ?>