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

// echo '<pre>';
// print_r($roleMap);
// echo '</pre>';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accountId'])) {
    $accountId = $_POST['accountId'];
    $roleId = $_POST['roleId'];
    $user = $userController->getUserById($accountId);
    $role = $roleController->getRoleById($roleId);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-account'])) {
    ob_clean(); // 👈 Xóa mọi output trước đó
    $accountId = $_POST['id'];
    $roleId = $_POST['role'];
    $result = $userController->updateUser($accountId, $roleId);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật vai trò thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cập nhật vai trò thất bại']);
    }
    exit; // 👈 Rất quan trọng để ngăn thêm output sau JSON
}

?>

<form id="update-account-form">
    <h2>Cập nhật tài khoản nhân viên</h2>

    <div class="form-group phuc">

        <input type="hidden" name="update-account" value="1">
        <input type="hidden" name="id" value="<?= $accountId ?>">

        <label for="name">Vai trò</label>
        <select name="role" id="role">
            <?php foreach ($roleMap as $id => $data): ?>
                <option value="<?= $data['id'] ?>" <?= ($user && $user->getRoleId() == $data['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($data['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <!-- <input type="text" value="<?= (int)($role->getId()) ?>"> -->
    </div>

    <button type="submit" class="btn" style="width: 100%;">CẬP NHẬT</button>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $('#update-account-form').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: 'ajax-handler/account/update.php',
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