<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoes Store</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="/ShoesStore/public/assets/images/apple-icon.png">
    <link rel="shortcut icon" type="images/x-icon" href="/ShoesStore/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="/ShoesStore/public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ShoesStore/public/assets/css/templatemo.css">
    <link rel="stylesheet" href="/ShoesStore/public/assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="/ShoesStore/public/assets/css/fontawesome.min.css">

</head>

<body">
    <!-- Header -->
     <div id="header">
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h2 align-self-center" href="Route.php?page=Product&action=showList&pageNumber=1">
                Shoes Store
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link ajaxLink" id="headerShowList" href="Route.php?page=Product&action=showList&pageNumber=1">Shop</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link ajaxLink" id="headerAbout" href="Route.php?page=Payment&action=orderhistory">Order History</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link ajaxLink" href="Route.php?page=Home&action=home">Contact</a>
                        </li>

                        <li class="nav-item d-flex align-items-center">
                            <select class="form-select form-select-sm" style="width: 140px;">
                                <option value="">Category</option>
                                <option value="category1">Adidas</option>
                                <option value="category2">Sport</option>
                                <option value="category3">Nike</option>
                            </select>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <select class="form-select form-select-sm" style="width: 140px;">
                                <option value="">Price</option>
                                <option value="low">Below 200.000đ</option>
                                <option value="medium">200.000đ - 500.000đ</option>
                                <option value="high">Above 500.000đ</option>
                            </select>
                        </li>
                        <!-- <li class="nav-item d-flex align-items-center">
                            <div class="input-group input-group-sm" style="max-width: 140px;">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <span class="input-group-text" style="cursor: pointer;">
                                    <i class="fa fa-fw fa-search"></i>
                                </span>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 ms-auto me-3">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search ...">
                    <span class="input-group-text" style="cursor: pointer;">
                        <i class="fa fa-fw fa-search"></i>
                    </span>
                </div>
            </div>
            <!-- <div class="d-flex align-items-center" style="width: 100%; max-width: 700px; margin-right: 14px;">
                <div class="input-group w-100">
                    <select class="form-select" style="font-size: 13px; width: 12%; box-shadow: none; border: 2px solid black;">
                        <option value="">Category</option>
                        <option value="category1">Adidas</option>
                        <option value="category2">Sport</option>
                        <option value="category3">Nike</option>
                    </select>

                    <select class="form-select" style="font-size: 13px; width: 20%; box-shadow: none; border: 2px solid black;">
                        <option value="">Price</option>
                        <option value="low">Below 200.000đ</option>
                        <option value="medium">200.000đ - 500.000đ</option>
                        <option value="high">Above 500.000đ</option>
                    </select>

                    <input type="text" class="form-control" style="width: 50%; border: 2px solid black; box-shadow: none; font-weight: 400 !important; font-size: 13px !important;" placeholder="Search ...">
                    <span class="input-group-text" style="cursor: pointer;">
                        <i class="fa fa-fw fa-search"></i>
                    </span>
                </div>
            </div> -->

            <div class="d-flex align-items-center gap-2">
                <a id="headerShowCart" class="nav-icon position-relative text-decoration-none ajaxLink" style="margin-right: 14px;" href="Route.php?page=Cart&action=showCart">
                    <i class="fa fa-fw fa-cart-arrow-down text-dark" style="font-size: 24px; margin-top: 6px;"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                </a>
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Nếu người dùng đã đăng nhập, hiển thị icon logout -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-icon text-decoration-none" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user text-dark" style="font-size: 24px; margin-top: 6px;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item ajaxLink" href="Route.php?page=Payment&action=orderhistory">Order history</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a id="headerLogout" class="dropdown-item" href="Route.php?page=Auth&action=logout">Log out</a>
                            </li>
                        </ul>
                    </div>
                    <?php else: ?>
                        <!-- Nếu người dùng chưa đăng nhập, hiển thị icon login -->
                        <a class="nav-icon position-relative text-decoration-none ajaxLink" href="Route.php?page=Auth&action=auth">
                            <i class="fas fa-user text-dark" style="font-size: 24px; margin-top: 6px;"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                        </a>
                    <?php endif; ?>
            </div>
        </div>
    </nav>


    <!-- Close Header -->
<div id="header">