<head>
    <link rel="stylesheet" href="../../../public/assets/css/cart.css">
</head>

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
                                    <div class="cart_item_image"><img class="img-fluid object-fit" src="../../../public/assets/images/test2.jpg" alt=""></div>
                                    <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                        <div class="cart_item_name cart_info_col">
                                            <div class="cart_item_title">Name</div>
                                            <div class="cart_item_text"><?php echo $cartDetail->name ?></div>
                                        </div>
                                        <div class="cart_item_color cart_info_col">
                                            <div class="cart_item_title">Color</div>
                                            <div class="cart_item_text"><?php echo $cartDetail->name?></div>
                                        </div>
                                        <div class="cart_item_quantity cart_info_col">
                                            <div class="cart_item_title">Quantity</div>
                                            <div class="cart_item_text"><?php echo $cartDetail->quantity?></div>
                                        </div>
                                        <div class="cart_item_price cart_info_col">
                                            <div class="cart_item_title">Price</div>
                                            <div class="cart_item_text"><?php echo number_format($cartDetail->price, 0, ',', '.')?></div>
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
                        <a href="Route.php?page=Product&action=showList&pageNumber=1" type="button" class="button cart_button_clear">Continue Shopping</a>
                        <a type="button" class="button cart_button_checkout">Go To Payment</a>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function sum($cartDetailList) {
    $total = 0;
    foreach ($cartDetailList as $item) {
        $total += $item->price * $item->quantity;
    }
    return $total;
}
?>