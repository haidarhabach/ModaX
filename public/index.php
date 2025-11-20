<!DOCTYPE html>
<html>
<?php
session_start();
// $_SESSION['cart'][] = [
//   'id' => 1,
//   'name' => 'White Shirt Pleat',
//   'price' => 19.00,
//   'qty' => 1,
//   'image' => 'images/item-cart-01.jpg'
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
    <nav class="navbar navbar-expand-lg sticky-top navbar-light" style="background-color: transparent !important; box-shadow: none !important;">

        <div class="container">
            <span class="logo md-0"><b>Moda</b>X</span>


            <!--mobile toggle-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!--Navigation menu-->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="homeDropdown" role="button" data-bs-toggle="dropdown">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product.php">Shop</a>
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



    <script>
        // Update the cart icon in the navigation header
        document.addEventListener('DOMContentLoaded', function () {
            const cartIcon = document.querySelector('.icon-container a');
            if (cartIcon) {
                cartIcon.setAttribute('data-bs-toggle', 'offcanvas');
                cartIcon.setAttribute('data-bs-target', '#cartSidebar');
                cartIcon.setAttribute('href', '#');
            }
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

        
    </script>




    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>




</html>
