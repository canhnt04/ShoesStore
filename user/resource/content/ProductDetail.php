<?php
require_once __DIR__ . "/../../../config/init.php";

// echo '<pre>';
// print_r($product);
// echo '</pre>';
?>

<div id="ajaxLoad">
    <!-- Open Content -->
    <section class="bg-light" id="prDetail">
        <div class="container pb-5">
            <div class="row" id="loadProductDetails">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img src="/ShoesStore/admin/uploads/<?= htmlspecialchars($product['thumbnail']) ?>"
                            alt="Hình ảnh sản phẩm"
                            height="300"
                            onerror="this.onerror=null;this.src='/DoAn/ShoesStore/public/assets/images/no_image.png';">
                    </div>
                </div>

                <!-- col end -->
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2"><?php echo $product['name'] ?></h1>
                            <p class="h3 py-2"><?php echo number_format($productDetailsSelected['price'], 0, ',', '.') . ' VND'; ?></p>

                            <p class="py-2">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-secondary"></i>
                                <span class="list-inline-item text-dark">Rating 4.8</span>
                            </p>

                            <!-- <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Brand:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p><?php echo $product['brand'] ?></p>
                                </li>
                            </ul> -->

                            <h6>Description:</h6>
                            <p><?php echo !empty($productDetailsSelected['description']) ? $productDetailsSelected['description'] : 'None'; ?></p>

                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Avaliable Size :</h6>
                                </li>
                                <li class="list-inline-item">
                                    <?php foreach ($sizeList as $item) {
                                        $class = "text-decoration-none ";
                                        $class .= $item['id'] == $productDetailsSelected['id'] ? "text-white btn-sm btn-success" : "text-muted"; ?>
                                        <a href='index.php?page=Product&action=showById&id=<?php echo $product['id'] ?>&pr_id=<?php echo $item['id'] ?>' class='<?php echo $class ?>'><strong><?php echo $item['size'] ?></strong></a>
                                    <?php } ?>
                                </li>
                            </ul>

                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Available Color:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <?php foreach ($colorList as $item) {
                                        $class = "text-decoration-none ";
                                        $class .= $item['color'] == $productDetailsSelected['color'] ? "text-white btn-sm  btn-success" : "text-muted"; ?>
                                        <a href='index.php?page=Product&action=showById&id=<?php echo $product['id'] ?>&pr_id=<?php echo $item['id'] ?>' class='<?php echo $class ?>'><strong><?php echo $item['color'] ?></strong></a>
                                    <?php } ?>
                                </li>
                            </ul>

                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Quantity:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p><?php echo $productDetailsSelected['quantity'] ?></p>
                                </li>
                            </ul>

                            <form id="buyProductForm" action="index.php?page=Cart&action=buyProduct" method="POST">
                                <input type="hidden" name="page" value="Product">
                                <input type="hidden" name="pr_id" id="pr_id" value="<?php echo $product['id'] ?>">
                                <input type="hidden" name="prdetail_id" id="prdetail_id" value="<?php echo $productDetailsSelected['id'] ?>">

                                <div class="row">
                                    <div class="col-auto">
                                        <ul class="list-inline pb-3">
                                            <li class="list-inline-item text-right">
                                                Quantity
                                                <input type="hidden" name="product-quanity" id="product-quanity" value="1">
                                            </li>
                                            <li class="list-inline-item">
                                                <span class="btn-sm btn-success" id="btn-minus">
                                                    <i class="fas fa-minus"></i>
                                                </span>
                                            </li>
                                            <li class="list-inline-item"><span class="badge bg-secondary" id="var-value">1</span></li>
                                            <li class="list-inline-item"><span class="btn-sm btn-success" id="btn-plus">+</span></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row pb-3">
                                    <!-- <div class="col d-grid">
                                        <button id="buyProductBtn" type="submit" class="btn btn-success btn-lg" name="action" value="buyProduct">Buy</button>
                                    </div> -->
                                    <div class="col d-grid">
                                        <a id="addToCartBtn" href="index.php?page=Cart&action=addToCart" class="btn btn-success btn-lg">Add To Cart</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content -->
</div>
<script>
    $(document).ready(function() {

        let btnMinus = document.getElementById("btn-minus");
        let btnPlus = document.getElementById("btn-plus");
        let valueDisplay = document.getElementById("var-value");

        btnMinus.addEventListener("click", () => {
            alert("Số lượng tối thiểu phải là 1.");
            let currentValue = parseInt(valueDisplay.textContent);
            if (currentValue > 0) currentValue--;
            valueDisplay.textContent = currentValue;
            document.getElementById("product-quanity").value = currentValue;
        });

        btnPlus.addEventListener("click", () => {
            let currentValue = parseInt(valueDisplay.textContent);
            currentValue++;
            valueDisplay.textContent = currentValue;
            document.getElementById("product-quanity").value = currentValue;
        });
    })
</script>