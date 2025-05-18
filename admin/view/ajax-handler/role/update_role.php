<?php
include_once __DIR__ . '/../../../controller/RoleController.php';
include_once __DIR__ . '/../../../../config/init.php';

header('Content-Type: application/json');

$database = new Database();
$connection = $database->getConnection();
$roleController = new RoleController($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roleId'])) {
    $roleId = $_POST['roleId'];
    $role = $roleController->getRoleById($roleId);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $result = $roleController->updateRole($id, $name);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật vai trò thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cập nhật vai trò thất bại']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tên vai trò không được để trống']);
    }

    exit; // Dừng lại sau khi trả JSON
}

?>

<!-- Form chỉnh sửa vai trò -->
<form id="update-role-form">
    <h2>Chỉnh sửa vai trò</h2>
    <input type="hidden" name="id" value="<?= htmlspecialchars($role->getId()) ?>" />
    <input type="hidden" name="update_role" value="1" /> <!-- THÊM DÒNG NÀY -->

    <div class="form-group phuc">
        <label for="name">Tên vai trò</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($role->getName()) ?>" />
    </div>

    <button type="submit" class="btn" style="width: 100%;">CẬP NHẬT</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $('#update-role-form').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: 'ajax-handler/role/update_role.php',
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