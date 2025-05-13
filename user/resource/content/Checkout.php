<head>
    <link rel="stylesheet" href="../../../public/assets/css/payment.css">
</head>
<div id="ajaxLoad">
    <main id="main" role="main">
        <section id="checkout-container">
            <form class="needs-validation" id="submitForm" action="Route.php?page=Payment&action=placeorder" method="POST">
                <div class="container">
                    <div class="row py-5">
                        <div class="sticky-top col-md-4 order-md-2 mb-4">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Your cart</span>
                                <span class="badge badge-secondary badge-pill">3</span>
                            </h4>
                            <ul class="list-group mb-3">
                            <?php $cart = isset($_SESSION["cartSession"]) ? $_SESSION["cartSession"] : []; ?>
                                <?php foreach ($cart as $cartItem) { ?>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0"><?= $cartItem["product_name"] ?></h6>
                                        <small class="text-muted">
                                            Quanity: <?= $cartItem["quantity"] ?>
                                        </small>
                                        <small class="text-muted">
                                             - Color: <?= $cartItem["color"] ?>
                                        </small>
                                    </div>
                                    <span class="text-muted">
                                       <?= number_format($cartItem["price"] * $cartItem["quantity"], 0, ',', '.') ?>
                                    </span>
                                </li>
                                <?php } ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (VND)</span>
                                    <strong><?= number_format(sum($cart), 0, "," , ".") ?></strong>
                                </li>
                            </ul>
                            <button class="button payment_checkout" type="submit">Place Order</button>
                        </div>
                        <div class="col-md-8 order-md-1">
                            <h4 class="mb-3">Your address info</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName">First name</label>
                                    <input type="text" class="form-control" id="firstName" disabled placeholder="" value="">
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName">Last name</label>
                                    <input type="text" class="form-control" id="lastName" disabled placeholder="" value="">
                                    <!-- <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div> -->
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="username">Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="username" disabled placeholder="Username" required>
                                    <!-- <div class="invalid-feedback" style="width: 100%;">
                                        Your username is required.
                                    </div> -->
                                </div>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" disabled  placeholder="you@example.com" required>
                                <div class="invalid-feedback">
                                    Please enter a valid email address for shipping updates.
                                </div>
                            </div> -->

                            <div class="mb-3">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                                <div class="invalid-feedback">
                                    Please enter your shipping address.
                                </div>
                            </div>

                            <hr class="mb-4">

                            <h4 class="mb-3">Payment Method</h4>

                            <div class="d-block my-3">
                                <div class="custom-control custom-radio">
                                    <input id="cash" name="paymentMethod" value="1" type="radio" class="custom-control-input" checked required>
                                    <label class="custom-control-label" for="cash">Cash</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="credit" name="paymentMethod" value="2" type="radio" class="custom-control-input" required>
                                    <label class="custom-control-label" for="credit">Credit card</label>
                                </div>
                            </div>

                            <!-- render credit -->
                            <div id="creditDiv">

                            </div>
                            
                            <hr class="mb-4">
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
</div>
<script>

    document.getElementById("credit").addEventListener("change", creditChecked);

    function creditChecked() {
        let isCheckedCredit = document.getElementById("credit").checked;
        if (isCheckedCredit) {
            renderCreditDiv()
        }
    }

    function renderCreditDiv() {
        let content = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cc-name">Name on card</label>
                <input type="text" class="form-control" id="cc-name" placeholder="" required>
                <small class="text-muted">Full name as displayed on card</small>
                <div class="invalid-feedback">
                    Name on card is required
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="cc-number">Credit card number</label>
                <input type="text" class="form-control" id="cc-number" placeholder="" required>
                <div class="invalid-feedback">
                    Credit card number is required
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="cc-expiration">Expiration</label>
                <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                <div class="invalid-feedback">
                    Expiration date required
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="cc-cvv">CVV</label>
                <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                <div class="invalid-feedback">
                    Security code required
                </div>
            </div>
        </div>`;

        let creditDiv = document.getElementById("creditDiv");
        creditDiv.innerHTML = content;
    }

    document.getElementById("cash").addEventListener("change", cashChecked);
    function cashChecked() {
        let isCashBtnChecked = document.getElementById("cash").checked;
        if(isCashBtnChecked){
            document.getElementById("creditDiv").innerHTML = "";
        }
    }
</script>
<?php
function sum($cart) {
    $sum = 0;
    foreach($cart as $item) {
        $sum += $item["price"] * $item["quantity"];
    }
    return $sum;
} 
?>
