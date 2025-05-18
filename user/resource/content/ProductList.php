<div id="ajaxLoad">
    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">

            <div class="col-lg-3">
                <h1 class="h2 pb-4">Category</h1>
                <ul class="list-unstyled templatemo-accordion">
                    <li class="pb-3">
                        <a class="collapsed d-flex justify-content-between h3 text-decoration-none" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" href="#collapseThree">
                            Category
                            <i class="pull-right fa fa-fw fa-chevron-circle-down mt-1" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" href="#collapseThree"></i>
                        </a>
                        <ul id="collapseThree" class="collapse list-unstyled pl-3">
                            <?php
                            foreach ($categoryList as $category) { ?>
                                <li>
                                    <a class="text-decoration-none ajaxLink"
                                        href="Route.php?page=Product&action=showByCategory&category=<?php echo $category['id'] ?>&pageNumber=1"><?php echo $category['name'] ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-inline shop-top-menu pb-3 pt-1">
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3 ajaxLink"
                                    href="Route.php?page=Product&action=showList&pageNumber=1">All</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 pb-4">
                        <div class="d-flex">
                            <select class="form-control">
                                <option>A to Z</option>
                                <option>Z to A</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="loadProduct">
                    <?php
                    // Kiểm tra nếu biến $productList có dữ liệu
                    if (isset($productList) && !empty($productList)) {
                        foreach ($productList as $product) { ?>
                            <div class="col-md-4">
                                <div class="card mb-4 product-wap rounded-0">
                                    <div class="card rounded-0">
                                        <img class="card-img rounded-0 img-fluid" src="../.../../public/assets/images/test2.jpg" />
                                    </div>
                                    <div class="card-body">
                                        <p class="h3"><?= $product->name ?></p>

                                        <?php
                                        // Duyệt chi tiết sản phẩm
                                        if (isset($product->productDetailsList)) {
                                            $colorList = [];
                                            foreach ($product->productDetailsList as $detail) {
                                                if (!in_array($detail["color"], $colorList)) {
                                                    $colorList[] = $detail["color"];  ?>
                                                    <a href="Route.php?page=Product&action=showById&id=<?= $detail["product_id"] ?>&pr_id=<?= $detail["id"] ?>"
                                                        class='text-muted mb-0 text-decoration-none'><?= $detail['color'] ?></a>

                                        <?php }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="col-md-4">
                            <p class="text-dark">Không có sản phẩm nào.</p>
                        </div>
                    <?php } ?>
                    <ul class="pagination pagination-lg justify-content-end">
                        <?php for ($index = 1; $index <= $totalPage; $index++) { ?>
                            <li class="page-item">
                                <a class="page-link rounded-0 shadow-sm border-top-0 border-left-0 text-dark"
                                    href="Route.php?page=Product&action=<?php echo $paginationName;
                                                                        if (isset($categoryId)) echo '&category=' . $categoryId ?>&pageNumber=<?php echo $index ?>"><?php echo $index ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <!-- End Content -->
</div>