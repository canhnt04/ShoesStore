<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../public/assets/css/admin_style/index.css">
    <link rel="stylesheet" href="../../public/assets/css/reset.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_style/header.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_style/dashboard.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_style/content.css">
    <!-- <link rel="stylesheet" href="./css/account_manager.css"> -->
    <link rel="stylesheet" href="./css/customer_manager.css">
    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <?php
    include './Header.php';
    ?>
    <div class="container">
        <?php
        include './Dashboard.php';
        include './Content.php';
        ?>
    </div>
</body>

</html>