<div id="ajaxLoad">
<div class="container my-5">
<div class="card shadow-lg border-0 rounded-4">
<div class="card-body">
    <div class="card-header bg-gradient bg-dark text-success rounded-top-4 px-4 py-3">
            <h4 class="mb-0 fw-bold">
                Order History
            </h4>
        </div>
    <table class="table table-hover table-sm align-middle text-center">
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Paymethod</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderList as $order) { ?>
                <tr>
                    <td><?= $order->created_at ?></td>
                    <td><?= number_format($order->total_price, 0, ',', '.') ?> đ</td>
                    <td><?= $order->status ?></td>
                    <td><?= $order->paymethod ?></td>
                    <td><a class="nav-link text-success" href="Route.php?page=Payment&action=showorder&id=<?= $order->id ?>">Details</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>