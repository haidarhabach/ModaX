<!DOCTYPE html>
<html>
<?php
session_start();
?>
<head>
    <title>About</title>
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
        /* Hero Section */
        .page-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/bg-01.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
        }
        /* About Section */
        .about-section {
            padding: 100px 0;
        }
        
        .about-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-color);
            position: relative;
            padding-bottom: 15px;
        }
        
        .about-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .about-text {
            color: var(--text-light);
            font-size: 16px;
            margin-bottom: 25px;
        }
        
        .about-image {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .about-image:hover {
            transform: translateY(-5px);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .image-border {
            border: 10px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        /* Mission Section */
        .mission-section {
            padding: 80px 0;
            background-color: var(--light-bg);
        }
        
        .mission-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-color);
            position: relative;
            padding-bottom: 15px;
        }
        
        .mission-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        /* Quote Block */
        .quote-block {
            border-left: 4px solid var(--primary-color);
            padding: 25px 30px;
            background-color: white;
            border-radius: 0 10px 10px 0;
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .quote-text {
            font-size: 18px;
            font-style: italic;
            color: var(--text-color);
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .quote-author {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 16px;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background-color: white;
        }
        
        .stat-item {
            text-align: center;
            padding: 30px;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 16px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Team Section */
        .team-section {
            padding: 80px 0;
            background-color: var(--light-bg);
        }
        
        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 50px;
            color: var(--text-color);
        }
        
        .team-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
        }
        
        .team-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .team-info {
            padding: 25px;
        }
        
        .team-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-color);
        }
        
        .team-role {
            color: var(--primary-color);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        
        /* Contact Info */
        .contact-info-box {
            background-color: var(--primary-color);
            color: white;
            padding: 40px;
            border-radius: 10px;
            margin-top: 50px;
        }
        
        .contact-info-box h4 {
            color: white;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .contact-info-box p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .contact-info-box i {
            margin-right: 10px;
            width: 20px;
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
                    <a href="../public/contact.php" class="me-3">Help & FAQs</a>
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
                        <a class="nav-link" href="index.php">
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
            <h2>About</h2>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <div class="pe-lg-5">
                        <h2 class="about-title">Our Story</h2>
                        <p class="about-text">
                            ModaX was born from a simple idea: fashion should be expressive, accessible, and effortless. What started as a passion for contemporary style has grown into a brand that celebrates individuality and confidence through clothing.

At ModaX, we carefully curate collections that blend modern trends with timeless essentials, ensuring every piece feels both current and enduring. From everyday wear to standout outfits, our goal is to help you look good and feel confident—wherever life takes you.

We believe fashion is more than clothing; it’s a statement of who you are. That’s why quality, comfort, and attention to detail are at the heart of everything we do.
                        </p>
                        <p class="about-text">
                            Every ModaX collection is inspired by real people, real lifestyles, and real moments. We work closely with designers and manufacturers to ensure our products meet high standards of craftsmanship while remaining affordable.

Whether you’re dressing for work, a night out, or a casual weekend, ModaX is here to be part of your journey—one outfit at a time.
                        </p>
                        <p class="about-text">
                            Have a question or need styling advice? Visit us in store or reach out anytime—our team is always happy to help you find your perfect look.
                        </p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about-image">
                        <img src="assets/images/about-01.jpg" alt="Our Store">
                    </div>
                </div>
            </div>
            
            <!-- Mission Section -->
            <div class="row align-items-center">
                <div class="col-lg-5 order-lg-1 order-2">
                    <div class="about-image mb-5 mb-lg-0">
                        <img src="assets/images/about-02.jpg" alt="Our Mission">
                    </div>
                </div>
                <div class="col-lg-7 order-lg-2 order-1 mb-5 mb-lg-0">
                    <div class="ps-lg-5">
                        <h2 class="about-title">Our Mission</h2>
                        <p class="about-text">
                           Our mission at ModaX is to empower self-expression through fashion. We aim to provide stylish, high-quality clothing that fits seamlessly into modern life—without compromising comfort or affordability.

We are committed to staying ahead of trends while maintaining a strong focus on durability, fit, and design. Every item we offer is selected to help you build a wardrobe you can rely on, season after season.
                        </p>
                        <p class="about-text">
                           ModaX is more than a store—it’s a community of people who value confidence, creativity, and individuality. We continuously evolve to meet our customers’ needs, embracing innovation while staying true to our brand identity.

Your style is personal. Our mission is to support it.
                        </p>
                        
                        <!-- Quote Block -->
                        <div class="quote-block">
                            <p class="quote-text">
                                Creativity is just connecting things. When you ask creative people how they did something, they feel a little guilty because they didn't really do it, they just saw something. It seemed obvious to them after a while.
                            </p>
                            <p class="quote-author">- Steve Jobs</p>
                        </div>
                    </div>
                </div>
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
    
    </body>
</html>
