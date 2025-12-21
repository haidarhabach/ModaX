<!DOCTYPE html>
<html>
<?php
session_start();
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
            overflow-x: hidden;
        }
        
        /* Mobile Header */
        .mobile-header {
            background-color: white;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-mobile img {
            height: 40px;
        }
        
        .icon-header-item {
            font-size: 20px;
            color: var(--text-color);
            margin-left: 15px;
            position: relative;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .icon-header-item:hover {
            color: var(--primary-color);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hamburger {
            width: 30px;
            height: 24px;
            position: relative;
            cursor: pointer;
        }
        
        .hamburger-line {
            position: absolute;
            width: 100%;
            height: 3px;
            background-color: var(--text-color);
            transition: all 0.3s ease;
        }
        
        .hamburger-line:nth-child(1) {
            top: 0;
        }
        
        .hamburger-line:nth-child(2) {
            top: 10px;
        }
        
        .hamburger-line:nth-child(3) {
            top: 20px;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg);
            top: 10px;
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg);
            top: 10px;
        }
        
        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }
        
        .mobile-menu.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        .topbar-mobile {
            background-color: var(--dark-bg);
            color: var(--text-light);
            padding: 10px 15px;
            font-size: 14px;
            margin: 0;
        }
        
        .main-menu-m {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .main-menu-m li {
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }
        
        .main-menu-m li a {
            display: block;
            padding: 15px;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .main-menu-m li a:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }
        
        .arrow-main-menu {
            position: absolute;
            right: 15px;
            top: 15px;
            color: var(--text-light);
            transition: transform 0.3s ease;
        }
        
        .arrow-main-menu.rotate {
            transform: rotate(90deg);
        }
        
        .sub-menu-m {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: var(--light-bg);
            display: none;
        }
        
        .sub-menu-m.show {
            display: block;
        }
        
        .sub-menu-m li a {
            padding-left: 30px;
            font-size: 14px;
        }
        
        .hot-label {
            position: absolute;
            top: 10px;
            right: 15px;
            background-color: #ff4d4d;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
        }
        
        /* Search Modal */
        .search-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1001;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .search-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .search-container {
            width: 90%;
            max-width: 600px;
        }
        
        .search-input {
            width: 100%;
            padding: 20px;
            font-size: 18px;
            border: none;
            background: transparent;
            border-bottom: 2px solid white;
            color: white;
        }
        
        .search-input:focus {
            outline: none;
        }
        
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .close-search {
            position: absolute;
            top: 30px;
            right: 30px;
            color: white;
            font-size: 30px;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close-search:hover {
            color: var(--primary-color);
        }
        
        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            max-width: 400px;
            height: 100%;
            background-color: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 1002;
            overflow-y: auto;
        }
        
        .cart-sidebar.show {
            transform: translateX(0);
        }
        
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .cart-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        /* Hero Section */
        .page-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/bg-01.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        
        .page-hero h2 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        /* Contact Form */
        .contact-section {
            padding: 100px 0;
        }
        
        .contact-form, .contact-info {
            background-color: white;
            border: 1px solid var(--border-color);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .contact-form h4, .contact-info h4 {
            font-size: 24px;
            margin-bottom: 30px;
            color: var(--text-color);
        }
        
        .form-control-custom {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(113, 127, 224, 0.2);
            outline: none;
        }
        
        .form-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background-color: var(--light-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary-color);
            margin-right: 20px;
        }
        
        .contact-detail h5 {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--text-color);
        }
        
        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .submit-btn:hover {
            background-color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(113, 127, 224, 0.3);
        }
        
        /* Map Section */
        .map-container {
            height: 400px;
            background-color: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }
        
        .map-placeholder {
            text-align: center;
            color: var(--text-light);
        }
        
        .map-placeholder i {
            font-size: 60px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .page-hero h2 {
                font-size: 36px;
            }
            
            .contact-form, .contact-info {
                padding: 25px;
            }
            
            .cart-sidebar {
                max-width: 100%;
            }
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

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <h2>Contact</h2>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="contact-form">
                        <h4 class="text-center mb-4">Send Us A Message</h4>
                        
                        <form>
                            <div class="mb-4 position-relative">
                                <input type="email" class="form-control-custom" placeholder="Your Email Address" required>
                                <span class="form-icon"><i class="fas fa-envelope"></i></span>
                            </div>
                            
                            <div class="mb-4">
                                <textarea class="form-control-custom" rows="6" placeholder="How Can We Help?" required></textarea>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="contact-info">
                        <!-- Address -->
                        <div class="d-flex mb-5">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-detail">
                                <h5>Address</h5>
                                <p class="mb-0">ModaX Store Center 8th floor, Beirut, Ouzai</p>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="d-flex mb-5">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-detail">
                                <h5>Let's Talk</h5>
                                <p class="mb-0 text-primary">+961 78 765 159</p>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="d-flex">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-detail">
                                <h5>Sale Support</h5>
                                <p class="mb-0 text-primary">Modax@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <div class="map-container">
         <!--33.847169469563795, 35.48763561031694-->
        <div id="map" style="width: 100%; height: 100%;"></div>
    </div>

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
        // Form submission
        document.querySelector('.contact-form form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
        // Back to top functionality
        const backToTopBtn = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
    </script>

<script>
        function initMap() {
  var location = { lat: 33.847169469563795, lng: 35.48763561031694 }; 
  var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 17,
      center: location,
      styles: [
          { elementType: "geometry", stylers: [{ color: "#1e1e1e" }] },
          { elementType: "labels.text.stroke", stylers: [{ color: "#1e1e1e" }] },
          { elementType: "labels.text.fill", stylers: [{ color: "#ffffff" }] }
      ]
  });
  var marker = new google.maps.Marker({ position: location, map: map });
}
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes&callback=initMap"></script>
    
    
    

        
</body>
</html>
