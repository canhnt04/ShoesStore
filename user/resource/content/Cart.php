<div id="ajaxLoad">
    <div class="cart_section" id="cartDetail">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cart_container">
                        <div class="cart_title">Shopping Cart<small> (<?php echo count($cart->cartDetailList) ?> item in your cart) </small></div>
                        <div class="cart_items">
                            <ul class="cart_list">
                                <?php foreach ($cart->cartDetailList as $cartDetail) { ?>
                                    <li class="cart_item clearfix">
                                        <div class="cart_item_image">
                                            <img class="img-fluid object-fit" src="/ShoesStore/public/assets/images/test2.jpg" alt="">
                                        </div>
                                        <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                            <div class="cart_item_name cart_info_col">
                                                <div class="cart_item_title">Name</div>
                                                <div class="cart_item_text"><?php echo $cartDetail->name ?></div>
                                            </div>
                                            <div class="cart_item_color cart_info_col">
                                                <div class="cart_item_title">Color</div>
                                                <div class="cart_item_text"><?php echo $cartDetail->color  . " - " . $cartDetail->size ?></div>
                                            </div>
                                            <div class="cart_item_quantity cart_info_col">
                                                <div class="cart_item_title">Quantity</div>
                                                <div class="cart_item_text">
                                                    <button class="btn btn-secondary" onclick="updateQuantity(<?= $cartDetail->id ?>, -1, <?= $cartDetail->quantity ?> )">-</button>
                                                    <?php echo $cartDetail->quantity ?>
                                                    <button class="btn btn-secondary" onclick="updateQuantity(<?= $cartDetail->id ?>, 1, <?= $cartDetail->quantity ?>)">+</button>
                                                </div>
                                            </div>
                                            <div class="cart_item_price cart_info_col">
                                                <div class="cart_item_title">Price</div>
                                                <div class="cart_item_text"><?php echo number_format($cartDetail->price, 0, ',', '.') ?></div>
                                            </div>
                                            <div class="cart_item_price cart_info_col">
                                                <div class="cart_item_title">Total</div>
                                                <div class="cart_item_text"><?php echo number_format($cartDetail->price * $cartDetail->quantity, 0, ',', '.') ?></div>
                                            </div>
                                            <div class="cart_item_select cart_info_col">
                                                <div class="cart_item_title">Select</div>
                                                <div class="cart_item_checkbox">
                                                    <input type="checkbox"
                                                        product_id="<?php echo $cartDetail->product_id ?>" detail_id="<?php echo $cartDetail->prdetail_id ?>"
                                                        product_quantity="<?= $cartDetail->quantity ?>"
                                                        product_price="<?= $cartDetail->price ?>"
                                                        product_color="<?= $cartDetail->color ?>"
                                                        product_name="<?php echo $cartDetail->name ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="order_total">
                            <div class="order_total_content text-md-right">
                                <div class="order_total_title">Order Total:</div>
                                <div class="order_total_amount"><?php echo number_format(sum($cart->cartDetailList), 0, ',', '.'); ?></div>
                            </div>
                        </div>
                        <div class="cart_buttons">
                            <a href="index.php?page=Product&action=showList&pageNumber=1" type="button" class="button cart_button_clear ajaxLink">Continue Shopping</a>
                            <a href="index.php?page=Payment&action=checkout" id="btnCheckout" type="button" class="button cart_button_checkout">Go To Payment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    function sum($cartDetailList)
    {
        $total = 0;
        foreach ($cartDetailList as $item) {
            $total += $item->price * $item->quantity;
        }
        return $total;
    }
    ?>
</div>
<script>
    function updateQuantity(cartDetailId, quanity, oldQuantity) {
        if (oldQuantity + quanity < 1) {
            removeFromCart(cartDetailId);
            return;
        }
        let url = "index.php?page=Cart&action=updateQuantity";

        let data = {
            cartDetail_id: cartDetailId,
            quantity: quanity,
        };

        $.ajax({
            url: url,
            method: "POST",
            dataType: "html",
            data: data,
            success: function(data) {
                $("#ajaxLoad").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMessage = JSON.parse(xhr.responseText);
                alert(errorMessage.message);
            },
        });
    }

    function removeFromCart(cartDetailId) {
        let url = "index.php?page=Cart&action=removeFromCartDetail";

        let data = {
            cartDetail_id: cartDetailId,
        };

        $.ajax({
            url: url,
            method: "POST",
            dataType: "html",
            data: data,
            success: function(data) {
                $("#ajaxLoad").html(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var errorMessage = JSON.parse(xhr.responseText);
                alert(errorMessage.message);
            },
        });
    }
</script>