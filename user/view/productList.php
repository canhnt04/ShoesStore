<?php
    /* file productList.php sẽ được gọi bởi controller ProductController */

    // Kêu file Header ra
    include("../resource/shared/Header.php");
    
    include("../resource/content/ProductList.php");

    echo '<a href="../Route.php?controller=Product&action=home">Xem sản phẩm</a>';

    // Footer
    include("../resource/shared/Footer.php");
?>
