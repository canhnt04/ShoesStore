<?php
    /* file productList.php sẽ được gọi bởi controller ProductController */

    // Kêu file Header ra
    include("../resource/shared/Header.php");

    echo '<a href="../Route.php?controller=Home&action=home&id=1">Home Page</a>';
    echo '<a href="../Route.php?controller=Product&action=showList">Product List</a>';
    
    include("../resource/content/ProductList.php");

    // Footer
    include("../resource/shared/Footer.php");
?>
