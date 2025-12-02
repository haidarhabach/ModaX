<!DOCTYPE html>
<html>
<?php
session_start();
include 'db.php';
// $_SESSION['cart'][] = [
//   'id' => 1,
//   'name' => 'White Shirt Pleat',
//   'price' => 19.00,
//   'qty' => 1,
//   'image' => 'assets/images/item-cart-01.jpg'
//  ];

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$index]);
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        /* Custom styles for improved design */
        .category-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .category-nav .nav-link {
            font-weight: 600;
            padding: 0.5rem 0;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
            color: #666;
        }

        .category-nav .nav-link.active,
        .category-nav .nav-link:hover {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
        }

        .filter-box,
        .search-box {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }

        .filter-header,
        .search-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .filter-body,
        .search-body {
            padding: 1.5rem;
        }

        .filter-actions {
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-select,
        .form-control {
            border-radius: 6px;
            padding: 0.625rem 0.75rem;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .btn-filter {
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 6px;
        }

        .search-btn {
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 6px;
        }

        .filter-toggle {
            display: flex;
            gap: 0.75rem;
            margin-left: auto;
        }

        /* Active state for toggle buttons */
        .btn-toggle.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        @media (max-width: 768px) {
            .category-nav {
                gap: 1rem;
            }

            .filter-toggle {
                margin-left: 0;
                margin-top: 1rem;
                width: 100%;
                justify-content: flex-end;
            }
        }

        .product-grid {
            padding: 50px 0;
        }

        .product-item {
            margin-bottom: 30px;
        }

        .product-card {
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: auto;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .quick-view-btn {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 10px;
            transition: bottom 0.3s ease;
            text-decoration: none;
            font-size: 14px;
        }

        .product-card:hover .quick-view-btn {
            bottom: 0;
        }

        .product-info {
            padding: 15px 0;
        }

        .product-name {
            font-size: 16px;
            color: var(--text-color);
            text-decoration: none;
            margin-bottom: 5px;
            display: block;
            transition: color 0.3s ease;
        }

        .product-name:hover {
            color: var(--primary-color);
        }

        .product-price {
            font-size: 18px;
            font-weight: 500;
            color: var(--primary-color);
        }

        .wishlist-btn {
            background: none;
            border: none;
            color: #ccc;
            font-size: 18px;
            transition: color 0.3s ease;
            position: relative;
        }

        .wishlist-btn:hover {
            color: #ff4d4d;
        }

        .wishlist-btn.active {
            color: #ff4d4d;
        }

        .load-more-btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .load-more-btn:hover {
            background-color: rgba(0, 0, 0, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(113, 127, 224, 0.3);
        }

        .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: var(--text-light);
            padding: 75px 0 32px;
        }

        .footer h4 {
            color: white;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer ul li {
            padding-bottom: 10px;
        }

        .footer a {
            color: #999;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: #0d6efd;
        }

        .footer p {
            color: #999;
            font-size: 14px;
            line-height: 1.8;
        }

        .social-icons a {
            display: inline-block;
            font-size: 18px;
            margin-right: 16px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-3px);
        }

        .newsletter-form .form-control {
            background: transparent;
            border: none;
            border-bottom: 1px solid #999;
            border-radius: 0;
            color: #999;
            padding: 8px 0;
            margin-bottom: 20px;
        }

        .newsletter-form .form-control:focus {
            box-shadow: none;
            border-bottom-color: #999;
        }

        .newsletter-form .form-control::placeholder {
            color: var(--text-lighter);
        }

        .subscribe-btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 2px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .subscribe-btn:hover {
            background-color: white;
            color: #0d6efd;
            transform: translateY(-2px);
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .payment-methods a {
            margin: 0 8px;
            display: inline-block;
        }

        .payment-icon {
            width: 50px;
            height: 30px;
            background-color: #333;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }


        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: white;
            color: #0d6efd;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .footer {
                padding: 50px 0 20px;
            }

            .footer .col-sm-6 {
                margin-bottom: 30px;
            }
        }
    </style>
</head>


<body>
    <!--top Promo bar-->
    <div class="top-promo-bar">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="promo-text mb-2 mb-md-0">
                    Free shipping for standard order over 100$
                </div>

                <div class="right-links d-flex">
                    <a href="#" class="me-3">Help & FAQs</a>
                    <a href="#" class="me-3">My Account</a>
                    <a href="login.php" class="me-3">sign in</a>
                </div>
            </div>
        </div>
    </div>

    <!--Main navigation-->
    <nav class="navbar navbar-expand-lg sticky-top navbar-light"
        style="background-color: transparent !important; box-shadow: none !important;">

        <div class="container">
            <span class="logo md-0"><b>Moda</b>X</span>


            <!--mobile toggle-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!--Navigation menu-->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="#" id="homeDropdown" role="button" data-bs-toggle="dropdown">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Blog</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>

                <!-- Header Icons -->
                <div class="d-flex align-items-center">
                    <a href="#" class="header-icon" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search"></i>
                    </a>

                    <div class="icon-container">
                        <a href="cart.php" class="header-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                        <span class="cart-badge">0</span>
                    </div>

                    <a href="#" class="header-icon">
                        <i class="far fa-heart"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="What are you looking for?">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar-->

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="cartSidebarLabel">Your Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">

            <div class="cart-items-container" style="max-height: 400px; overflow-y: auto;">
                <ul class="list-group list-group-flush">

                    <?php
                    $total = 0;
                    if (!empty($_SESSION['cart'])):
                        foreach ($_SESSION['cart'] as $item):
                            $itemTotal = $item['price'] * $item['qty'];
                            $total += $itemTotal;
                            ?>

                            <li class="list-group-item border-0">
                                <div class="row align-items-center g-3">
                                    <div class="col-3">
                                        <img src="<?= $item['image'] ?>" class="img-fluid rounded" alt="<?= $item['name'] ?>">
                                    </div>

                                    <div class="col-9">
                                        <a href="#" class="text-decoration-none text-dark fw-semibold d-block mb-1">
                                            <?= $item['name'] ?>
                                        </a>

                                        <span class="text-muted small">
                                            <?= $item['qty'] ?> x $<?= number_format($item['price'], 2) ?>
                                        </span>

                                        <a href="?remove=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger mt-1">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>

                        <?php endforeach; else: ?>

                        <li class="list-group-item text-center py-4">
                            <em>Your cart is empty</em>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary border-top bg-light p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold text-primary">$<?= number_format($total, 2) ?></span>
                </div>

                <div class="d-grid gap-2">
                    <a href="cart.php" class="btn btn-outline-primary">View Cart</a>
                    <a href="checkout.php" class="btn btn-primary">Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <!--Hero Section-->

    <section class="hero-slider">

        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <!-- Slides -->
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="slide-background" style="background-image: url('assets/images/slide-01.jpg');"></div>
                    <div class="container">
                        <div class="carousel-content">
                            <div class="slide-text animated fadeInDown">
                                <span class="slide-subtitle">
                                    Women Collection 2024
                                </span>
                            </div>

                            <div class="slide-heading animated fadeInUp">
                                <h2 class="slide-title">
                                    NEW SEASON
                                </h2>
                            </div>

                            <div class="slide-action animated zoomIn">
                                <a href="product.php" class="btn btn-primary btn-shop">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="slide-background" style="background-image: url('assets/images/slide-02.jpg');"></div>
                    <div class="container">
                        <div class="carousel-content">
                            <div class="slide-text animated rollIn">
                                <span class="slide-subtitle">
                                    Men New-Season
                                </span>
                            </div>

                            <div class="slide-heading animated lightSpeedIn">
                                <h2 class="slide-title">
                                    Jackets & Coats
                                </h2>
                            </div>

                            <div class="slide-action animated slideInUp">
                                <a href="product.php" class="btn btn-primary btn-shop">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="slide-background" style="background-image: url('assets/images/slide-03.jpg');"></div>
                    <div class="container">
                        <div class="carousel-content">
                            <div class="slide-text animated rotateInDownLeft">
                                <span class="slide-subtitle">
                                    Men Collection 2024
                                </span>
                            </div>

                            <div class="slide-heading animated rotateInUpRight">
                                <h2 class="slide-title">
                                    New Arrivals
                                </h2>
                            </div>

                            <div class="slide-action animated rotateIn">
                                <a href="product.php" class="btn btn-primary btn-shop">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- banner section-->
    <section class="banner-section py-5">
        <div class="container">
            <div class="row g-4">

                <!-- banner 1 :women-->
                <div class="col-md-6 col-xl-4">
                    <div class="banner-card position-relative overflow-hidden rounded shadow-sm">
                        <img src="assets/images/banner-01.jpg" class="img-fluid w-100" alt="Women's Collection">
                        <a href="products.php?category=women"
                            class="banner-overlay position absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-4 text-decoration-none">
                            <div class="banner-content">
                                <h3 class="banner-title fw-bold mb-2">Women</h3>
                                <p class="banner-subtitle mb-0">Spring 2025</p>
                            </div>
                            <div class="banner-action">
                                <span class="banner-link fw-semibold">Shop Now</span>
                            </div>
                        </a>
                    </div>
                </div>



                <!-- banner 2 :men-->
                <div class="col-md-6 col-xl-4">
                    <div class="banner-card position-relative overflow-hidden rounded">
                        <img src="assets/images/banner-02.jpg" class="img-fluid w-100" alt="Women's Collection">
                        <a href="products.php?category=men"
                            class="banner-overlay position absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-4 text-decoration-none ">
                            <div class="banner-content">
                                <h3 class="banner-title fw-bold mb-2">men</h3>
                                <p class="banner-subtitle mb-0">Spring 2025</p>
                            </div>
                            <div class="banner-action">
                                <span class="banner-link fw-semibold">Shop Now</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- banner 3 :accessories-->
                <div class="col-md-6 col-xl-4">
                    <div class="banner-card position-relative overflow-hidden rounded">
                        <img src="assets/images/banner-03.jpg" class="img-fluid w-100" alt="Women's Collection">
                        <a href="products.php?category=accessories"
                            class="banner-overlay position absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-4 text-decoration-none ">
                            <div class="banner-content">
                                <h3 class="banner-title fw-bold mb-2">Accessories</h3>
                                <p class="banner-subtitle mb-0">New Trend</p>
                            </div>
                            <div class="banner-action">
                                <span class="banner-link fw-semibold">Shop Now</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- products section-->

    <section class="container my-5">
        <!--Category navigation-->
        <div class="section-header mb-4">
            <h2 class="section-title"> Product Overview </h2>
        </div>
        <div class="category-nav">
            <a href="index.php" class="nav-link <?= empty($_GET['category']) ? 'active' : '' ?>">All Products</a>
            <a href="index.php?category=women"
                class="nav-link <?= ($_GET['category'] ?? '') === 'women' ? 'active' : '' ?>">Women</a>
            <a href="index.php?category=men"
                class="nav-link <?= ($_GET['category'] ?? '') === 'men' ? 'active' : '' ?>">Men</a>
            <a href="index.php?category=bag"
                class="nav-link <?= ($_GET['category'] ?? '') === 'bag' ? 'active' : '' ?>">Bag</a>
            <a href="index.php?category=shoes"
                class="nav-link <?= ($_GET['category'] ?? '') === 'shoes' ? 'active' : '' ?>">Shoes</a>
            <a href="index.php?category=watches"
                class="nav-link <?= ($_GET['category'] ?? '') === 'watches' ? 'active' : '' ?>">Watches</a>

            <div class="filter-toggle">
                <button class="btn btn-outline-secondary btn-sm btn-toggle" data-bs-toggle="collapse"
                    data-bs-target="#filterBox" aria-expanded="false" aria-controls="filterBox">
                    <i class="fas fa-filter me-1"></i> Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm btn-toggle" data-bs-toggle="collapse"
                    data-bs-target="#searchBox" aria-expanded="false" aria-controls="searchBox">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </div>

        <!-- Filter and Search Boxes Container -->
        <div class="accordion" id="filterSearchAccordion">
            <!-- FILTER BOX -->
            <div class="collapse" id="filterBox" data-bs-parent="#filterSearchAccordion">
                <div class="filter-box">
                    <div class="filter-header">
                        <i class="fas fa-filter me-2"></i> Filter Products
                    </div>

                    <form method="GET" class="filter-body">
                        <?php if (!empty($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?= $_GET['category'] ?>">
                        <?php endif; ?>

                        <div class="row g-4">
                            <!-- SORT -->
                            <div class="col-md-3">
                                <label class="form-label">Sort By</label>
                                <select name="sort" class="form-select">
                                    <option value="">Default</option>
                                    <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price Low → High</option>
                                    <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price High → Low</option>
                                    <option value="newness" <?= ($_GET['sort'] ?? '') === 'Newness' ? 'selected' : '' ?>>
                                        Newness</option>
                                    <option value="popularity" <?= ($_GET['sort'] ?? '') === 'popularity' ? 'selected' : '' ?>>Popularity</option>
                                </select>
                            </div>

                            <!-- PRICE -->
                            <div class="col-md-3">
                                <label class="form-label">Price Range</label>
                                <select name="price" class="form-select">
                                    <option value="">All</option>
                                    <option value="0-50" <?= ($_GET['price'] ?? '') === '0-50' ? 'selected' : '' ?>>
                                        $0.00 - $50.00</option>
                                    <option value="50-100" <?= ($_GET['price'] ?? '') === '50-100' ? 'selected' : '' ?>>
                                        $50.00 - $100.00</option>
                                    <option value="100-150" <?= ($_GET['price'] ?? '') === '100-150' ? 'selected' : '' ?>>
                                        $100.00 - $150.00</option>
                                    <option value="150-200" <?= ($_GET['price'] ?? '') === '150-200' ? 'selected' : '' ?>>
                                        $150.00 - $200.00</option>
                                    <option value="200" <?= ($_GET['price'] ?? '') === '200' ? 'selected' : '' ?>>
                                        $200.00+</option>
                                </select>
                            </div>

                            <!-- COLOR -->
                            <div class="col-md-2">
                                <label class="form-label">Color</label>
                                <select name="color" class="form-select">
                                    <option value="">All</option>
                                    <option value="black" <?= ($_GET['color'] ?? '') === 'black' ? 'selected' : '' ?>>
                                        Black</option>
                                    <option value="white" <?= ($_GET['color'] ?? '') === 'white' ? 'selected' : '' ?>>
                                        White</option>
                                    <option value="red" <?= ($_GET['color'] ?? '') === 'red' ? 'selected' : '' ?>>Red
                                    </option>
                                    <option value="blue" <?= ($_GET['color'] ?? '') === 'blue' ? 'selected' : '' ?>>
                                        Blue</option>
                                    <option value="green" <?= ($_GET['color'] ?? '') === 'green' ? 'selected' : '' ?>>
                                        Green</option>
                                    <option value="grey" <?= ($_GET['color'] ?? '') === 'grey' ? 'selected' : '' ?>>
                                        Grey</option>
                                </select>
                            </div>

                            <!-- TAGS -->
                            <div class="col-md-3">
                                <label class="form-label">Tag</label>
                                <select name="tag" class="form-select">
                                    <option value="">All</option>
                                    <option value="fashion" <?= ($_GET['tag'] ?? '') === 'fashion' ? 'selected' : '' ?>>
                                        Fashion</option>
                                    <option value="lifestyle" <?= ($_GET['tag'] ?? '') === 'lifestyle' ? 'selected' : '' ?>>Lifestyle</option>
                                    <option value="denim" <?= ($_GET['tag'] ?? '') === 'denim' ? 'selected' : '' ?>>
                                        Denim</option>
                                    <option value="streetstyle" <?= ($_GET['tag'] ?? '') === 'streetstyle' ? 'selected' : '' ?>>Streetstyle</option>
                                    <option value="crafts" <?= ($_GET['tag'] ?? '') === 'crafts' ? 'selected' : '' ?>>
                                        Crafts</option>
                                </select>
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button class="btn btn-primary btn-filter">
                                <i class="fas fa-check me-1"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SEARCH BOX -->
            <div class="collapse" id="searchBox" data-bs-parent="#filterSearchAccordion">
                <div class="search-box">
                    <div class="search-header">
                        <i class="fas fa-search me-2"></i> Search Products
                    </div>

                    <form method="GET" class="search-body">
                        <?php if (!empty($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?= $_GET['category'] ?>">
                        <?php endif; ?>

                        <div class="row align-items-end">
                            <div class="col-md-9">
                                <label class="form-label">Search products</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Enter product name, description, or keyword..."
                                    value="<?= $_GET['search'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-dark search-btn w-100">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="product-grid">
        <div class="container">
<!-- hasan !! -->

<!-- 
-------------------------------------
data base query needed :
select price,name,price,filename,type from products as p,product_images as i
where  p.id = i.product_id;
-------------------------------------
-->
            <!-- Product Grid -->
            <div class="row" id="product-grid">
<?php
$stmt=$connect->prepare("select price,name,price,filename,type from products as p,product_images as i
where  p.id = i.product_id;");
$stmt->execute();
$result=$stmt->get_result();
while($row=$result->fetch_assoc())
{
    ?>
<div class="col-sm-6 col-md-4 col-lg-3 product-item $row['type']">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/<?= $row['filename'] ?>" alt="<?= $row['name'] ?>">
                            <a href="#" class="quick-view-btn">Quick View</a>
                        </div>
                        <div class="product-info d-flex justify-content-between align-items-start">
                            <div>
                                <a href="#" class="product-name"><?= $row['name'] ?></a>
                                <div class="product-price"><?= $row['price'] ?>$</div>
                            </div>
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
<?php
}

?>
</div>
                
            

            <!-- Load More Button -->
            <div class="text-center mt-5">
                <a class="load-more-btn" href="products.php">Load More</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-4">
                    <h4>Categories</h4>
                    <ul>
                        <li>
                            <a href="#">Women</a>
                        </li>
                        <li>
                            <a href="#">Men</a>
                        </li>
                        <li>
                            <a href="#">Shoes</a>
                        </li>
                        <li>
                            <a href="#">Watches</a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 mb-4">
                    <h4>Help</h4>
                    <ul>
                        <li>
                            <a href="#">Track Order</a>
                        </li>
                        <li>
                            <a href="#">Returns</a>
                        </li>
                        <li>
                            <a href="#">Shipping</a>
                        </li>
                        <li>
                            <a href="#">FAQs</a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 mb-4">
                    <h4>GET IN TOUCH</h4>
                    <p>
                        Any questions? Let us know in store at 8th floor, 379 Hudson St, New York, NY 10018 or call us
                        on (+1) 96 716 6879
                    </p>
                    <div class="social-icons pt-3">
                        <a href="#">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#">
                            <i class="fab fa-pinterest-p"></i>
                        </a>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-4">
                    <h4>Newsletter</h4>
                    <form class="newsletter-form">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="subscribe-btn w-100">
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="pt-5">
                <div class="payment-methods">
                    <a href="#">
                        <div class="payment-icon"><img src="assets/images/icons/icon-pay-01.png"></div>
                    </a>
                    <a href="#">
                        <div class="payment-icon"><img src="assets/images/icons/icon-pay-02.png"></div>
                    </a>
                    <a href="#">
                        <div class="payment-icon"><img src="assets/images/icons/icon-pay-03.png"></div>
                    </a>
                    <a href="#">
                        <div class="payment-icon"><img src="assets/images/icons/icon-pay-04.png"></div>
                    </a>
                    <a href="#">
                        <div class="payment-icon"><img src="assets/images/icons/icon-pay-05.png"></div>
                    </a>
                </div>


            </div>
        </div>
    </footer>

    <!-- Back to top -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </div>

    <script>
        // Update the cart icon in the navigation header
        document.addEventListener('DOMContentLoaded', function () {
            const cartIcon = document.querySelector('.icon-container a');
            if (cartIcon) {
                cartIcon.setAttribute('data-bs-toggle', 'offcanvas');
                cartIcon.setAttribute('data-bs-target', '#cartSidebar');
                cartIcon.setAttribute('href', '#');
            }

            // Add active state to toggle buttons
            const filterButtons = document.querySelectorAll('.btn-toggle');

            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Add active class to clicked button
                    this.classList.add('active');
                });
            });

            // Handle when a collapse is shown
            const filterBox = document.getElementById('filterBox');
            const searchBox = document.getElementById('searchBox');
            const filterBtn = document.querySelector('[data-bs-target="#filterBox"]');
            const searchBtn = document.querySelector('[data-bs-target="#searchBox"]');

            filterBox.addEventListener('show.bs.collapse', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                filterBtn.classList.add('active');
            });

            searchBox.addEventListener('show.bs.collapse', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                searchBtn.classList.add('active');
            });

            // Handle when both are closed
            filterBox.addEventListener('hidden.bs.collapse', function () {
                if (!searchBox.classList.contains('show')) {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                }
            });

            searchBox.addEventListener('hidden.bs.collapse', function () {
                if (!filterBox.classList.contains('show')) {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                }
            });
        });

        // Initialize carousel with custom settings
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = new bootstrap.Carousel('#heroCarousel', {
                interval: 5000, // 5 seconds
                pause: 'hover',
                wrap: true,
                touch: true
            });

            // Reset animations when slide changes
            document.getElementById('heroCarousel').addEventListener('slide.bs.carousel', function () {
                const activeSlide = this.querySelector('.carousel-item.active');
                const animatedElements = activeSlide.querySelectorAll('.animated');

                animatedElements.forEach(element => {
                    element.style.animation = 'none';
                    void element.offsetWidth; // Trigger reflow
                    element.style.animation = null;
                });
            });
        });

        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        backToTopButton.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
