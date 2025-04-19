<div id="ajaxLoad">
<!-- Open Content -->
<section class="bg-light" id="prDetail">
        <div class="container pb-5">
            <div class="row" id="loadProductDetails">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="../../../public/assets/images/test2.jpg" alt="Card image cap" id="product-detail">
                    </div>
                </div>

                <!-- col end -->
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2"><?php echo $product->name ?></h1>
                            <p class="h3 py-2"><?php echo number_format($productDetailsSelected->price, 0, ',', '.') . ' VND'; ?></p></p>
                            <p class="py-2">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-secondary"></i>
                                <span class="list-inline-item text-dark">Rating 4.8</span>
                            </p>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Brand:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-muted"><strong><?php echo $productDetailsSelected->brand ?></strong></p>
                                </li>
                            </ul>

                            <h6>Description:</h6>
                            <p>Test</p>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Avaliable Color :</h6>
                                </li>
                                <li class="list-inline-item">
                                    <?php 
                                    foreach($product->productDetailsList as $productDetails) {
                                        echo "<a href='Route.php?page=Product&action=showById&id={$product->id}&color={$productDetails->color}' class='text-muted'><strong>{$productDetails->color}</strong></a>";
                                    }?>
                                </li>
                            </ul>

                            <h6>Quantity:</h6>
                            <p> <?php echo $productDetailsSelected->quantity ?> </p>
                            
                            <!--action vừa get vừa Post -->
                            <!--GET: page=Product&action=buyProduct-->
                            <!--POST: Dữ liệu trong form -->
                            <form action="Route.php?page=Product&action=buyProduct" method="POST">
                                <input type="hidden" name="page" value="Product">
                                <input type="hidden" name="pr_id" id="pr_id" value="<?php echo $product->id ?>">
                                <input type="hidden" name="prdetail_id" id="prdetail_id" value="<?php echo $productDetailsSelected->id ?>">
                                <div class="row">
                                    <div class="col-auto">
                                        <ul class="list-inline pb-3">
                                            <li class="list-inline-item">Size :
                                                <input type="hidden" name="product-size" id="product-size" value="<?php echo $productDetailsSelected->size ?>">
                                            </li>
                                            <li class="list-inline-item"><span class="btn btn-success btn-size"><?php echo $productDetailsSelected->size ?></span></li>
                                        </ul>
                                    </div>
                                    <div class="col-auto">
                                        <ul class="list-inline pb-3">
                                            <li class="list-inline-item text-right">
                                                Quantity
                                                <input type="hidden" name="product-quanity" id="product-quanity" value="1">
                                            </li>
                                            <li class="list-inline-item"><span class="btn btn-success" id="btn-minus">-</span></li>
                                            <li class="list-inline-item"><span class="badge bg-secondary" id="var-value">1</span></li>
                                            <li class="list-inline-item"><span class="btn btn-success" id="btn-plus">+</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <!-- Điều hướng đến trang mua sắm -->
                                    <div class="col d-grid">
                                        <button id="buyProductBtn" type="submit" class="btn btn-success btn-lg" name="action" value="buyProduct">Buy</button>
                                    </div>
                                    <!-- Tiếp tục mua sắm -->
                                    <div class="col d-grid">
                                        <a id="addToCartBtn" href="Route.php?page=Product&action=addToCart" class="btn btn-success btn-lg">Add To Cart</a>
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
    <script>
    document.getElementById("addToCartBtn").addEventListener("click", function(e) {
        e.preventDefault();
        
        var pr_id = document.getElementById("pr_id").value;
        var prdetail_id = document.getElementById("prdetail_id").value;
        var size = document.getElementById("product-size").value;
        var quanity = document.getElementById("product-quanity").value;

        const formData = new FormData();
        formData.append("pr_id", pr_id);
        formData.append("prdetail_id", prdetail_id);
        formData.append("product-size", size);
        formData.append("product-quanity", quanity);

        $.ajax({
            url: "Route.php?page=Product&action=addToCart",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#loadProduct').html('<p style="height:400px;">Đang tải dữ liệu...</p>');
            },
            success: function (data) {
                if(confirm("Thêm giỏ hàng thành công! Xem giỏ hàng?")) {
                    $('#prDetail').html($(data).find('#cartDetail').html());
                    window.location.href = "Route.php?page=Product&action=addToCart";
                }
            },
            error: function () {
                alert('Lỗi khi tải dữ liệu sản phẩm.');
            }
        });
    });
    </script>
    </div>