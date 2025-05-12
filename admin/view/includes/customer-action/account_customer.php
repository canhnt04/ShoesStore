<?php
include_once __DIR__ . '/../../../controller/CustomerController.php';

$customerController = new CustomerController();
$customers = $customerController->listCustomers(); // Lấy danh sách trực tiếp từ Controller

?>
<div class="account-action">
    <div class="search-box">
        <input type="text" class="input_search_account-action" placeholder="Tìm kiếm tài khoản" />
    </div>

</div>

<?php if (!empty($customers)): ?>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Tên khách hàng</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= htmlspecialchars($customer->getId()) ?></td>
                <td><?= htmlspecialchars($customer->getFullName()) ?></td>
                <td><?= htmlspecialchars($customer->getEmail()) ?></td>
                <td><?= htmlspecialchars($customer->getPhone()) ?></td>
                <td><?= htmlspecialchars($customer->getAddress()) ?></td>
                <td class="table_col-action">
                    <span><i class="fa-solid fa-eye"></i></span>
                    <span><i class="fa-solid fa-lock"></i></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Không khách hàng nào.</p>
<?php endif; ?>