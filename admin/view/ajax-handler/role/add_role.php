<?php
include_once __DIR__ . '/../../../controller/RoleController.php';
include_once __DIR__ . '/../../../../config/init.php';


$database = new Database();
$connection = $database->getConnection();

$roleController = new RoleController($connection);

// Nếu là POST thì xử lý thêm vai trò
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        // Gọi hàm tạo vai trò
        $result = $roleController->addRole($name);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Thêm vai trò thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Thêm vai trò thất bại']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tên vai trò không được để trống']);
    }

    exit; // Chỉ xử lý logic khi gọi bằng POST
}
?>

<!-- Form thêm vai trò -->
<form id="add-role-form">
    <h2>Thêm vai trò</h2>
    <div class="form-group phuc">
        <label for="name">Tên vai trò</label>
        <input type="text" id="name" name="name" required />
    </div>
    <button type="submit" class="btn" style="width: 100%;">Thêm</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $('#add-role-form').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: 'ajax-handler/role/add_role.php',
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