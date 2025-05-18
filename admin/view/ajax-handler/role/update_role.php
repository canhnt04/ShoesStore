<?php
include_once __DIR__ . '/../../../controller/RoleController.php';
include_once __DIR__ . '/../../../../config/init.php';

$database = new Database();
$connection = $database->getConnection();
$roleController = new RoleController($connection);

// Biến để lưu vai trò
$role = null;

// 1. Xử lý cập nhật vai trò qua Ajax
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cập nhật vai trò
    if (isset($_POST['update_role'])) {
        $id = $_POST['id'];
        $name = trim($_POST['name']);

        if (!empty($name)) {
            $result = $roleController->updateRole($id, $name);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Cập nhật vai trò thành công' : 'Cập nhật vai trò thất bại'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tên vai trò không được để trống']);
        }

        exit; // Trả về JSON và kết thúc
    }

    // Hiển thị form chỉnh sửa vai trò
    if (isset($_POST['roleId'])) {
        $roleId = $_POST['roleId'];
        $role = $roleController->getRoleById($roleId);
    }
}
?>

<?php if ($role): ?>
    <!-- Form chỉnh sửa vai trò -->
    <form id="update-role-form">
        <h2>Chỉnh sửa vai trò</h2>
        <input type="hidden" name="id" value="<?= htmlspecialchars($role->getId()) ?>" />

        <div class="form-group phuc">
            <label for="name">Tên vai trò</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($role->getName()) ?>" required />
        </div>

        <button type="submit" name="update_role" class="btn" style="width: 100%;">CẬP NHẬT</button>
    </form>
<?php else: ?>
    <p>Không tìm thấy vai trò để chỉnh sửa.</p>
<?php endif; ?>

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
                    location.reload();
                }
            },
            error: function() {
                alert('Có lỗi xảy ra.');
            }
        });
    });
</script>