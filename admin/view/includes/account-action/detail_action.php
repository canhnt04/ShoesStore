<?php
include_once __DIR__ . '/../../../controller/EmployeeController.php';

$page = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$limit = 10; // Số lượng phần tử mỗi trang
$offset = ($page - 1) * $limit;

$employeeController = new EmployeeController();

// Lấy danh sách nhân và van tổng số nhân viên  
$employees = $employeeController->listEmployees($limit, $offset);
$totalEmployees = $employeeController->countEmployees();
$totalPages = ceil($totalEmployees / $limit);
?>
<div class="account-action detail-tab">
    <div class="search-box">
        <input type="text" class="input_search_account-action siuu" placeholder="Tìm kiếm nhân viên" />
    </div>
</div>

<?php if (!empty($employees)): ?>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Họ và tên</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Lương</th>
            <th>Xem chi tiết</th>
        </tr>
        <?php foreach ($employees as $emp): ?>
            <tr>
                <td><?= htmlspecialchars($emp->getId()) ?></td>
                <td><?= htmlspecialchars($emp->getFullname()) ?></td>
                <td><?= htmlspecialchars($emp->getPhone()) ?></td>
                <td><?= htmlspecialchars($emp->getAddress()) ?></td>
                <td><?= htmlspecialchars($emp->getSalary()) ?></td>
                <td class="table_col-action">
                    <span><i class="fa-solid fa-eye"></i></span>
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
    <p>Không có nhân viên nào.</p>
<?php endif; ?>