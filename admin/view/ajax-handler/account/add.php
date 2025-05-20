<?php
include_once __DIR__ . '/../../../controller/UserController.php';
include_once __DIR__ . '/../../../controller/RoleController.php';
include_once __DIR__ . '/../../../../config/init.php';

$database = new Database();
$connection = $database->getConnection();

$userController = new UserController($connection);
$roleController = new RoleController($connection);


// Lấy danh sách các role
$roles = $roleController->getAllRolesWithoutPagination();
$roleMap = [];
foreach ($roles as $role) {
    $roleMap[$role->getId()] = [
        'id' => (int)$role->getId(),
        'name' => $role->getName()
    ];
}
// Xử lý bỏ đi role "Khách hàng" và "Admin"
foreach ($roleMap as $key => $role) {
    if ($role['id'] === 4 || $role['id'] === 1) {
        unset($roleMap[$key]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-account'])) {
    ob_clean(); // 👈 Xóa mọi output trước đó

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $result = $userController->createUser($username, $password, $email, $role);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Tạo tài khoản thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tạo tài khoản thất bại']);
    }
    exit;
}

?>

<form id="add-account-form">
    <h2>Thêm tài khoản nhân viên</h2>

    <input type="hidden" name="add-account" value="1">

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
        <label for="">Vai trò nhân viên</label>
        <select name="role" id="role">
            <?php foreach ($roleMap as $id => $data): ?>
                <option value="<?= $data['id'] ?>">
                    <?= htmlspecialchars($data['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn">GỬI</button>
</form>



<script>
    $('#add-account-form').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: 'ajax-handler/account/add.php',
            method: 'POST',
            data: formData,
            success: function(response) {
                let data = JSON.parse(response);
                alert(data.message);

                if (data.status === 'success') {
                    $('#order-modal').fadeOut();
                    location.reload(); // hoặc gọi lại AJAX để làm mới bảng
                }
            },
            error: function() {
                alert('Có lỗi xảy ra.');
            }
        });
    });
</script>