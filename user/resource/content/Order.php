<div id="ajaxLoad">
<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient bg-dark text-success rounded-top-4 px-4 py-3">
            <h4 class="mb-0 fw-bold">
                Order Detail
            </h4>
        </div>
        <div class="card-body p-4">
            <div class="table">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($orderDetails as $index => $item): ?>
                            <?php
                                $subtotal = $item->quantity * $item->price;
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= $item->name ?></td>
                                <td><?= $item->quantity ?></td>
                                <td><?= number_format($item->price, 0, ',', '.') ?></td>
                                <td><?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-danger fw-bold"><?= number_format($total, 0, ',', '.') ?> đ</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-end mt-4">
                <!-- <button class="btn btn-outline-secondary" onclick="window.print()">
                    Print
                </button> -->
                <a href="Route.php?page=Product&action=showList&pageNumber=1" class="btn btn-primary">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

</div>