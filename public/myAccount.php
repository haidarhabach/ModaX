<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Modax</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0b5ed7;
            --text-dark: #333;
            --text-light: #777;
            --bg-dark: #121212;
            --bg-light: #f8f9fa;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background-color: var(--bg-light);
            line-height: 1.6;
        }

        

        

        

        

        /* Dashboard Layout */
        .dashboard-container {
            min-height: calc(100vh - 200px);
        }

        /* Sidebar */
        .dashboard-sidebar {
            position: sticky;
            top: 30px;
            height: fit-content;
        }

        .sidebar-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .user-profile {
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 4px solid var(--primary-color);
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .profile-email {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-button {
            display: block;
            width: 100%;
            text-align: left;
            border: none;
            background: none;
            display: flex;
            align-items: center;
            padding: 15px 30px;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
            border-left: 4px solid transparent;
            cursor: pointer;
        }

        .menu-button:hover,
        .menu-button.active {
            background-color: rgba(233, 30, 99, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .menu-button i {
            width: 25px;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        /* Main Content Area */
        .content-section {
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .content-section.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Stats Cards */
        .stats-grid {
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(233, 30, 99, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* Orders Card */
        .orders-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--text-dark);
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        /* Order Categories */
        .order-category {
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
        }

        .category-header {
            padding: 20px;
            background-color: rgba(233, 30, 99, 0.05);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-title {
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .category-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .delivered-icon {
            background-color: rgba(76, 175, 80, 0.15);
            color: #4caf50;
        }

        .processing-icon {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .cancelled-icon {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        .order-count {
            background-color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .delivered-count {
            color: #4caf50;
            border: 1px solid #4caf50;
        }

        .processing-count {
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .cancelled-count {
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .category-body {
            padding: 0;
        }

        /* Order Items */
        .order-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background-color: rgba(233, 30, 99, 0.03);
        }

        .product-image-small {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .product-image-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .order-info {
            flex: 1;
        }

        .order-product-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .order-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .order-id {
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-dates {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
            min-width: 150px;
        }

        .order-date {
            font-weight: 500;
            margin-bottom: 3px;
        }

        .order-status {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            margin-left: 20px;
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-details {
            background-color: rgba(233, 30, 99, 0.1);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-details:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-track {
            background-color: rgba(33, 150, 243, 0.1);
            color: #2196f3;
            border-color: #2196f3;
        }

        .btn-track:hover {
            background-color: #2196f3;
            color: white;
        }

        .btn-cancel {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-cancel:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Wishlist Section */
        .wishlist-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
        }

        .wishlist-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .wishlist-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .product-image {
            width: 160px;
            height: 160px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 25px;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .wishlist-item:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .product-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .spec-item {
            display: flex;
            align-items: center;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .spec-item i {
            color: var(--primary-color);
            margin-right: 5px;
        }

        .product-price {
            text-align: right;
        }

        

        .original-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
        }

        .btn-add-to-cart {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-add-to-cart:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        /* Profile Section */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }

        .profile-form .form-group {
            margin-bottom: 25px;
        }

        .profile-form label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .profile-form .form-control {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            transition: var(--transition);
        }

        .profile-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.2);
        }

        .btn-update {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-update:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        /* Sign Out Section */
        .signout-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 50px 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .signout-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .signout-message {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: var(--text-light);
        }

        .btn-confirm-signout {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-confirm-signout:hover {
            background: #dc3545;
            color: white;
        }

        /* Order Filters */
        .order-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .filter-btn {
            padding: 8px 20px;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        .filter-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Status badges */
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-delivered {
            background-color: rgba(76, 175, 80, 0.15);
            color: #4caf50;
        }

        .status-processing {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--border-color);
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-sidebar {
                position: static;
                margin-bottom: 30px;
            }

            .order-item {
                flex-wrap: wrap;
            }

            .product-image-small {
                width: 100%;
                height: 200px;
                margin-right: 0;
                margin-bottom: 15px;
            }

            .order-dates {
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
                margin-top: 15px;
            }

            .order-actions {
                width: 100%;
                justify-content: center;
                margin-left: 0;
                margin-top: 15px;
            }

            .wishlist-item {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                width: 100%;
                height: 250px;
                margin-right: 0;
                margin-bottom: 20px;
            }

            .product-price {
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 15px;
            }

            .table tbody td {
                display: block;
                text-align: right;
                padding: 10px 15px;
                position: relative;
            }

            .table tbody td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: 600;
                color: var(--text-dark);
            }

            .stat-number {
                font-size: 2rem;
            }

            .category-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .stats-grid .col-6 {
                margin-bottom: 20px;
            }

            .orders-card,
            .wishlist-card,
            .profile-card,
            .signout-card {
                padding: 20px;
            }

            .product-specs {
                justify-content: center;
            }

            .order-details {
                flex-direction: column;
                gap: 5px;
            }

            .order-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-action {
                width: 100%;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
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
    
    <!-- Dashboard Content -->
    <section class="dashboard-container">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="dashboard-sidebar">
                        <div class="sidebar-card fade-in">
                            <div class="user-profile">
                                
                                <h3 class="profile-name">haidar habach</h3>
                                <p class="profile-email">haidarhabach@gmail.com</p>
                            </div>

                            <div class="sidebar-menu">
                                <button class="menu-button active" data-section="dashboard">
                                    <i class="fas fa-home"></i>
                                    <span>Dashboard</span>
                                </button>
                                <button class="menu-button" data-section="profile">
                                    <i class="fas fa-user"></i>
                                    <span>My Profile</span>
                                </button>
                                <button class="menu-button" data-section="orders">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>My Orders</span>
                                </button>
                                <button class="menu-button" data-section="wishlist">
                                    <i class="fas fa-heart"></i>
                                    <span>Wishlist</span>
                                </button>
                                <button class="menu-button" data-section="signout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Sign Out</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Dashboard Content (Default) -->
                    <div id="dashboard-section" class="content-section active">
                        <!-- Stats Cards -->
                        <div class="row stats-grid g-4 fade-in">
                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div class="stat-number">15</div>
                                    <div class="stat-label">Total Orders</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="stat-number">5</div>
                                    <div class="stat-label">Active Coupons</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="stat-number">23</div>
                                    <div class="stat-label">Wishlist Items</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-dollar"></i>
                                    </div>
                                    <div class="stat-number">154</div>
                                    <div class="stat-label">Total Spent</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div class="orders-card fade-in">
                            <h3 class="card-title">Recent Orders</h3>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Product</th>
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>Order Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="order-id">#ST2456</td>
                                            <td><strong>Premium Denim Jacket</strong></td>
                                            <td>M</td>
                                            <td>Blue</td>
                                            <td>March 2, 2023</td>
                                            <td>$89.99</td>
                                            <td><span class="status-badge status-delivered">Delivered</span></td>
                                        </tr>
                                        <tr>
                                            <td class="order-id">#ST2457</td>
                                            <td><strong>Classic White Sneakers</strong></td>
                                            <td>42</td>
                                            <td>White</td>
                                            <td>March 8, 2023</td>
                                            <td>$79.99</td>
                                            <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                        </tr>
                                        <tr>
                                            <td class="order-id">#ST2458</td>
                                            <td><strong>Summer Floral Dress</strong></td>
                                            <td>S</td>
                                            <td>Multicolor</td>
                                            <td>March 6, 2023</td>
                                            <td>$65.50</td>
                                            <td><span class="status-badge status-processing">Processing</span></td>
                                        </tr>
                                        <tr>
                                            <td class="order-id">#ST2459</td>
                                            <td><strong>Casual Linen Shirt</strong></td>
                                            <td>L</td>
                                            <td>Beige</td>
                                            <td>March 13, 2023</td>
                                            <td>$45.00</td>
                                            <td><span class="status-badge status-delivered">Delivered</span></td>
                                        </tr>
                                        <tr>
                                            <td class="order-id">#ST2460</td>
                                            <td><strong>Wool Blend Coat</strong></td>
                                            <td>M</td>
                                            <td>Black</td>
                                            <td>March 7, 2023</td>
                                            <td>$129.99</td>
                                            <td><span class="status-badge status-processing">Processing</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Wishlist Items -->
                        <div class="wishlist-card fade-in">
                            <h3 class="card-title">Wishlist Items</h3>

                            <!-- Wishlist Item 1 -->
                            <div class="wishlist-item">
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                        alt="Leather Biker Jacket">
                                </div>

                                <div class="product-info">
                                    <h4 class="product-name">Premium Leather Biker Jacket</h4>
                                    <div class="product-specs">
                                        <div class="spec-item">
                                            <i class="fas fa-ruler"></i> Size: M
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-palette"></i> Color: Black
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-tag"></i> Category: Outerwear
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="product-price">
                                    
                                    <div class="original-price">$159.99</div>
                                    <button class="btn-add-to-cart">Add to Cart</button>
                                </div>
                            </div>

                            <!-- Wishlist Item 2 -->
                            <div class="wishlist-item">
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                        alt="Casual Hoodie">
                                </div>

                                <div class="product-info">
                                    <h4 class="product-name">Oversized Casual Hoodie</h4>
                                    <div class="product-specs">
                                        <div class="spec-item">
                                            <i class="fas fa-ruler"></i> Size: L
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-palette"></i> Color: Grey
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-tag"></i> Category: Casual
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="product-price">
                                    <div class="original-price">$69.99</div>
                                
                                    <button class="btn-add-to-cart">Add to Cart</button>
                                </div>
                            </div>

                            <!-- Wishlist Item 3 -->
                            <div class="wishlist-item">
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                        alt="Formal Suit">
                                </div>

                                <div class="product-info">
                                    <h4 class="product-name">Classic Fit Formal Suit</h4>
                                    <div class="product-specs">
                                        <div class="spec-item">
                                            <i class="fas fa-ruler"></i> Size: 42R
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-palette"></i> Color: Navy Blue
                                        </div>
                                        <div class="spec-item">
                                            <i class="fas fa-tag"></i> Category: Formal
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="product-price">
                                    <div class="original-price">$299.99</div>
                                    
                                    <button class="btn-add-to-cart">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Content -->
                    <div id="profile-section" class="content-section">
                        <div class="profile-card fade-in">
                            <h3 class="card-title">My Profile</h3>
                            <p class="text-muted mb-4">Update your personal information and preferences</p>

                            <form class="profile-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first-name">First Name</label>
                                            <input type="text" class="form-control" id="first-name" value="Haidar">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last-name">Last Name</label>
                                            <input type="text" class="form-control" id="last-name" value="habach">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="haidarhabach@gmail.com">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" value="+1 234 567 8900">
                                </div>

                                <div class="form-group">
                                    <label for="address">Shipping Address</label>
                                    <input type="text" class="form-control" id="address"
                                        value="123 Fashion Street, New York, NY 10001">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password" class="form-control" id="password"
                                                placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirm-password">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm-password"
                                                placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-update">Update Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Orders Content -->
                    <div id="orders-section" class="content-section">
                        <div class="orders-card fade-in">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="card-title mb-0">My Orders</h3>
                                <div class="order-filters">
                                    <button class="filter-btn active" data-filter="all">All Orders</button>
                                    <button class="filter-btn" data-filter="delivered">Delivered</button>
                                    <button class="filter-btn" data-filter="processing">Processing</button>
                                    <button class="filter-btn" data-filter="cancelled">Cancelled</button>
                                </div>
                            </div>
                            <p class="text-muted mb-4">View and manage all your clothing orders</p>

                            <!-- Delivered Orders -->
                            <div class="order-category delivered-category" data-category="delivered">
                                <div class="category-header">
                                    <div class="category-title">
                                        <div class="category-icon delivered-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <span>Delivered Orders</span>
                                    </div>
                                    <div class="order-count delivered-count">8 Orders</div>
                                </div>
                                <div class="category-body">
                                    <!-- Order Item 1 -->
                                    <div class="order-item">
                                        <div class="product-image-small">
                                            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                                alt="Leather Jacket">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-product-name">Premium Denim Jacket</div>
                                            <div class="order-details">
                                                <span class="order-id">#ST2456</span>
                                                <span>Size: M | Color: Blue</span>
                                                <span>Quantity: 1</span>
                                                <span class="text-success"><i
                                                        class="fas fa-check-circle me-1"></i>Delivered on Mar 5, 2023</span>
                                            </div>
                                        </div>
                                        <div class="order-dates">
                                            <div class="order-date">Ordered: Mar 2, 2023</div>
                                            <div class="order-date">Delivered: Mar 5, 2023</div>
                                            <div class="order-status">$89.99</div>
                                        </div>
                                        <div class="order-actions">
                                            
                                            <button class="btn-action btn-track">Buy Again</button>
                                        </div>
                                    </div>

                                    <!-- Order Item 2 -->
                                    <div class="order-item">
                                        <div class="product-image-small">
                                            <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                                alt="Casual Shirt">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-product-name">Casual Linen Shirt</div>
                                            <div class="order-details">
                                                <span class="order-id">#ST2459</span>
                                                <span>Size: L | Color: Beige</span>
                                                <span>Quantity: 2</span>
                                                <span class="text-success"><i
                                                        class="fas fa-check-circle me-1"></i>Delivered on Mar 15, 2023</span>
                                            </div>
                                        </div>
                                        <div class="order-dates">
                                            <div class="order-date">Ordered: Mar 13, 2023</div>
                                            <div class="order-date">Delivered: Mar 15, 2023</div>
                                            <div class="order-status">$90.00</div>
                                        </div>
                                        <div class="order-actions">
                                            
                                            <button class="btn-action btn-track">Buy Again</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Processing Orders -->
                            <div class="order-category processing-category" data-category="processing">
                                <div class="category-header">
                                    <div class="category-title">
                                        <div class="category-icon processing-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <span>Processing Orders</span>
                                    </div>
                                    <div class="order-count processing-count">3 Orders</div>
                                </div>
                                <div class="category-body">
                                    <!-- Order Item 1 -->
                                    <div class="order-item">
                                        <div class="product-image-small">
                                            <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                                alt="Summer Dress">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-product-name">Summer Floral Dress</div>
                                            <div class="order-details">
                                                <span class="order-id">#ST2458</span>
                                                <span>Size: S | Color: Multicolor</span>
                                                <span>Quantity: 1</span>
                                                <span class="text-warning"><i class="fas fa-clock me-1"></i>Expected delivery: Mar 12, 2023</span>
                                            </div>
                                        </div>
                                        <div class="order-dates">
                                            <div class="order-date">Ordered: Mar 6, 2023</div>
                                            <div class="order-date">Expected: Mar 12, 2023</div>
                                            <div class="order-status">$65.50</div>
                                        </div>
                                        <div class="order-actions">
                                            
                                            <button class="btn-action btn-cancel">Cancel</button>
                                        </div>
                                    </div>

                                    <!-- Order Item 2 -->
                                    <div class="order-item">
                                        <div class="product-image-small">
                                            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                                alt="Winter Coat">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-product-name">Wool Blend Coat</div>
                                            <div class="order-details">
                                                <span class="order-id">#ST2460</span>
                                                <span>Size: M | Color: Black</span>
                                                <span>Quantity: 1</span>
                                                <span class="text-warning"><i class="fas fa-clock me-1"></i>Expected delivery: Mar 15, 2023</span>
                                            </div>
                                        </div>
                                        <div class="order-dates">
                                            <div class="order-date">Ordered: Mar 7, 2023</div>
                                            <div class="order-date">Expected: Mar 15, 2023</div>
                                            <div class="order-status">$129.99</div>
                                        </div>
                                        <div class="order-actions">
                                            
                                            <button class="btn-action btn-cancel">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancelled Orders -->
                            <div class="order-category cancelled-category" data-category="cancelled">
                                <div class="category-header">
                                    <div class="category-title">
                                        <div class="category-icon cancelled-icon">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <span>Cancelled Orders</span>
                                    </div>
                                    <div class="order-count cancelled-count">4 Orders</div>
                                </div>
                                <div class="category-body">
                                    <!-- Order Item 1 -->
                                    <div class="order-item">
                                        <div class="product-image-small">
                                            <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80"
                                                alt="White Sneakers">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-product-name">Classic White Sneakers</div>
                                            <div class="order-details">
                                                <span class="order-id">#ST2457</span>
                                                <span>Size: 42 | Color: White</span>
                                                <span>Quantity: 1</span>
                                                <span class="text-danger"><i
                                                        class="fas fa-times-circle me-1"></i>Cancelled on Mar 9, 2023</span>
                                            </div>
                                        </div>
                                        <div class="order-dates">
                                            <div class="order-date">Ordered: Mar 8, 2023</div>
                                            <div class="order-date">Cancelled: Mar 9, 2023</div>
                                            <div class="order-status">$79.99</div>
                                        </div>
                                        <div class="order-actions">
                                            
                                            <button class="btn-action btn-track">Reorder</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wishlist Content -->
                    <div id="wishlist-section" class="content-section">
                        <div class="wishlist-card fade-in">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="card-title mb-0">My Wishlist</h3>
                                
                            </div>
                            <p class="text-muted mb-4">Save your favorite items for later purchase</p>

                            <!-- Wishlist items will be populated here -->
                            <div class="wishlist-items-container">
                                <!-- Items are loaded from the dashboard section -->
                            </div>
                        </div>
                    </div>

                    <!-- Sign Out Content -->
                    <div id="signout-section" class="content-section">
                        <div class="signout-card fade-in">
                            <div class="signout-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <h3 class="card-title">Sign Out</h3>
                            <p class="signout-message">Are you sure you want to sign out of your account?</p>
                            <p class="text-muted mb-4">You will need to sign in again to access your dashboard.</p>

                            <div class="d-flex justify-content-center gap-3">
                                <button class="btn btn-update" id="cancel-signout">Cancel</button>
                                <button class="btn btn-confirm-signout">Sign Out</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Main content switching functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Get all menu buttons and content sections
            const menuButtons = document.querySelectorAll('.menu-button');
            const contentSections = document.querySelectorAll('.content-section');

            // Function to switch content
            function switchContent(sectionId) {
                // Hide all content sections
                contentSections.forEach(section => {
                    section.classList.remove('active');
                });

                // Show the selected section
                const activeSection = document.getElementById(`${sectionId}-section`);
                if (activeSection) {
                    activeSection.classList.add('active');
                }

                // Update active button
                menuButtons.forEach(button => {
                    button.classList.remove('active');
                    if (button.getAttribute('data-section') === sectionId) {
                        button.classList.add('active');
                    }
                });

                // If switching to wishlist, copy items from dashboard
                if (sectionId === 'wishlist') {
                    const wishlistContainer = document.querySelector('.wishlist-items-container');
                    const wishlistItems = document.querySelectorAll('#dashboard-section .wishlist-item');
                    wishlistContainer.innerHTML = '';
                    wishlistItems.forEach(item => {
                        wishlistContainer.appendChild(item.cloneNode(true));
                    });
                }
            }

            // Add click event listeners to menu buttons
            menuButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const sectionId = this.getAttribute('data-section');
                    switchContent(sectionId);
                });
            });

            // Order filtering functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderCategories = document.querySelectorAll('.order-category');

            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.getAttribute('data-filter');

                    // Update active filter button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide categories based on filter
                    orderCategories.forEach(category => {
                        const categoryType = category.getAttribute('data-category');

                        if (filter === 'all' || filter === categoryType) {
                            category.style.display = 'block';
                        } else {
                            category.style.display = 'none';
                        }
                    });
                });
            });

            // Add responsive table labels
            const tableHeaders = document.querySelectorAll('.table thead th');
            const tableRows = document.querySelectorAll('.table tbody tr');

            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (tableHeaders[index]) {
                        cell.setAttribute('data-label', tableHeaders[index].textContent);
                    }
                });
            });

            

            // Add animation on scroll
            const observerOptions = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, observerOptions);

            const animatedElements = document.querySelectorAll('.sidebar-card, .stat-card, .orders-card, .wishlist-card, .profile-card, .signout-card');
            animatedElements.forEach(el => observer.observe(el));
        });
    </script>
</body>

</html>
