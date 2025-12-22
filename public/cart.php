<?php
// 20/12/hasan all backend :O
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}
include '../includes/db.php';
$coupon_cart = ["hasan"=>20 , "haidar"=>20 , "hammoud"=>10,"abdallah"=>5];
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {

    $cart = json_decode($_POST['cart'], true); // to get by name !!
    
    if(empty($cart))
    {
        echo "error";
        exit;
    }

    $cartTotal = (float) $_POST['cartTotal'];
    
    $shipping_country = $_SESSION['lastShippingChoise'] ?? '';

    $shippingTotal = (float) $_POST['shippingTotal'];
    
    $discountTotal = (float) $_POST['discountTotal'];
    
    $paymentMethod = $_POST['paymentMethod']; 
    
    $total = ($cartTotal > 100 ? $cartTotal : $cartTotal + $shippingTotal) - $discountTotal;

    $stmt = $connect->prepare("INSERT INTO orders (user_id,total,shipping_address, shipping, discount, payment_method, created_at) VALUES (? , ?,?, ?, ?, ?, NOW())");
    $stmt->bind_param("idsdds",$_SESSION['user_id'],$total,$shipping_country, $shippingTotal, $discountTotal, $paymentMethod);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();
    // save order item to see the status 
    foreach($cart as $item){
        $stmt = $connect->prepare("INSERT INTO order_items (order_id, product_id, quantity, product_name, price) VALUES (? ,?, ?, ?, ?)");
        $stmt->bind_param("iiisd", $order_id, $item['id'], $item['qty'],$item['name'], $item['price']);
        $stmt->execute();
        $stmt->close();
    }

    // delete cart after finish 
    $_SESSION['cart'] = [];
    $_SESSION['couponTotal'] = 0;
    $_SESSION['shipping'] = 0;
    // masseg lhrje3a ll ajax
    echo 'success';
    exit;
}
if(isset($_POST['coupon']))
{
    $coupon = strtolower(trim($_POST['coupon']));
    if(array_key_exists($coupon,$coupon_cart))
    {
        $couponTotal=$coupon_cart[$coupon];
    }
    else {
        $couponTotal=0;
    }
    $_SESSION['couponTotal']=$couponTotal;
    echo $couponTotal; // hay eza bdkon bs b3at l js l coupon ll php lezm php yredo llui ha lrad mtl sent
    exit; // for no overlap with the html js return full html page !!
}
?>
<?php
if(!isset($cartTotal))
{
    $cartTotal = 0;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $newQuantities = $_POST['qty'] ?? [];

    foreach ($_SESSION['cart'] as  &$item) {
        $key =$item['id'] ;
            if (isset($newQuantities[$key])) {
            $item['qty'] = (int) $newQuantities[$key];
        }
    }
    unset($item); // ha bs mshen a3ml unlink llpointer lmhtot 3l session lhez fo2 l & lsert sherha 700 mra
$cartTotal=0;
}

?>
<!DOCTYPE html>
<html>
<?php

// $_SESSION['cart'][] = [
//   'id' => 1,
//   'name' => 'White Shirt Pleat',
//   'price' => 19.00,
//   'qty' => 1,
//   'image' => 'assets/images/item-cart-01.jpg'
//  ];
$cart=$_SESSION['cart'] ?? [];
$total=0;
$shipping_discound=["United Status"=>20 , "United Kingdom"=>20 , "Lebanon"=>50 , "Canada"=>20 , "Syria"=>50];
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove']; 
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $remove_id) {
                unset($_SESSION['cart'][$index]);
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


?>

<head>
    <title>Cart</title>
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
            ;
            --primary-dark: #0b5ed7;
            --text-dark: #333;
            --text-light: #777;
            --bg-dark: #121212;
            --bg-light: #f8f9fa;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Cart Items */
        .cart-container {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .cart-header {
            background-color: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-header h3 {
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .cart-items {
            padding: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 25px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 120px;
            height: 120px;
            border-radius: 10px;
            overflow: hidden;
            margin-right: 25px;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .item-image:hover img {
            transform: scale(1.05);
        }

        .item-details {
            flex: 1;
        }

        .item-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .item-price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .item-remove {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
            padding: 5px;
        }

        .item-remove:hover {
            transform: scale(1.1);
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .qty-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .qty-input {
            width: 60px;
            height: 40px;
            text-align: center;
            border: 1px solid var(--border-color);
            border-left: none;
            border-right: none;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Cart Summary */
        .cart-summary {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
            height: fit-content;

            top: 30px;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--text-dark);
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-label {
            color: var(--text-light);
        }

        .summary-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 20px 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            border-top: 2px solid var(--border-color);
            margin-top: 10px;
        }

        .total-price {
            color: var(--primary-color);
        }

        /* Coupon Section */
        .coupon-section {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }

        .coupon-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .coupon-input-group {
            display: flex;
            gap: 15px;
        }

        .coupon-input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .coupon-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
            outline: none;
        }

        .btn-coupon {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-coupon:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Shipping Section */
        .shipping-section {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }

        .shipping-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .form-select,
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
            transition: var(--transition);
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
        }

        .btn-update {
            background: var(--bg-light);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-update:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 18px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: var(--transition);
            width: 100%;
            margin-top: 20px;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 60px 30px;
        }

        .empty-cart-icon {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-cart-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-light);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .item-image {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .quantity-selector {
                justify-content: center;
            }

            .coupon-input-group {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 40px 0;
            }

            .page-title {
                font-size: 2rem;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .cart-items {
                padding: 20px;
            }

            .item-image {
                width: 100px;
                height: 100px;
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

        .coupon-update-row {
            display: flex;
            gap: 20px;
            align-items: stretch;
        }

        .update-cart-wrapper {
            display: flex;
            align-items: flex-end;
        }

        @media (max-width: 768px) {
            .coupon-update-row {
                flex-direction: column;
            }

            .update-cart-wrapper button {
                width: 100%;
            }
        }

        .payment-methods1 {
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }

        .payment-option1 {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
            background: white;
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            background: var(--bg-light);
        }

        .payment-option input {
            margin-right: 10px;
        }

        .payment-option label {
            cursor: pointer;
            font-weight: 500;
            color: var(--text-dark);
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
        .display-content{
            display: contents;
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
                    <a href="../public/contat.php" class="me-3">Help & FAQs</a>
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
                        <a class="nav-link" href="index.php" >
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
<!-- hasan remove backend  -->


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
                            $cartTotal += $itemTotal;
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
    <!-- Shopping Cart -->
    <section class="shopping-cart-section mt-5">
        <div class="container">
            <div class="row">
                <!-- Cart Items -->
                <form class="display-content" method="post">
                <div class="col-lg-8">
                    <!-- Empty Cart Message (Hidden by default) -->
                    <div id="emptyCart" class="empty-cart" style="display: none;">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h2 class="empty-cart-title">Your cart is empty</h2>
                        <p>Looks like you haven't added any items to your cart yet.</p>
                        <a href="shop.html" class="btn-checkout">Continue Shopping</a>
                    </div>
                        <!-- Backend Hasan -->


                         <!-- // $_SESSION['cart'][] = [
                        //   'id' => 1,
                        //   'name' => 'White Shirt Pleat',
                        //   'price' => 19.00,
                        //   'qty' => 1,
                        //   'image' => 'assets/images/item-cart-01.jpg'
                        //  ]; -->
                    <!-- Cart with Items -->
                    <div id="cartWithItems" class="cart-container fade-in">
                        <div class="cart-header">
                            <h3>Your Cart Items <?= count($_SESSION['cart'] ?? []) ?></h3>
                        </div>
                        
                        <div class="cart-items">
                        <?php 
                        $i=1;
                        foreach($cart as $value)
                        {
                            $total+=$value['price'] * $value['qty'];
                        ?>  
                        <!-- Cart Item i -->
                            <div class="cart-item" id=<?php echo "item".$i; ?>>
                                <div class="item-image">
                                    <img src="assets/images/<?= $value['photo'] ?>"
                                        alt="<?= $value['name'] ?>">
                                </div>
                                <div class="item-details">
                                    <h4 class="item-title"><?= $value['name'] ?></h4>
                                    <div class="item-price"><?= $value['price']*$value['qty'] ?>$</div>

                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn qty-decrease" data-item="<?= $i ?>">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input class="qty-input" type="number" name="qty[<?= $value['id'] ?>]" value="<?= $value['qty'] ?>" min="1" data-price="<?= $value['price'] ?>"
                                            data-item="<?= $i ?>">
                                        <button type="button" class="qty-btn qty-increase" data-item="<?= $i ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="item-total ms-auto me-3">
                                    <div class="item-price" id="itemTotal1"><?= $value['price']*$value['qty'] ?>$</div>
                                </div>

                                <button type="button" class="item-remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                        <?php } ?>
                        </div>
                    </div> 
                    <!-- Coupon Section -->
                    <div class="coupon-update-row fade-in mb-5">
                        <div class="coupon-section flex-grow-1">
                            <h4 class="coupon-title">Have a coupon code?</h4>
                            <div class="coupon-input-group">
                                <input type="text" id="couponCode" class="coupon-input" placeholder="Enter coupon code">
                                <button type="button" id="applyCoupon" class="btn-coupon">Apply</button>
                            </div>
                        </div>
                            
                        <div class="update-cart-wrapper">
                            <button type="submit" name="update" class="btn-update h-100">
                                <i class="fas fa-sync-alt me-2"></i> Update Cart
                            </button>
                        </div>
                    </div>

                </div>
                        </form>
                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary fade-in">
                        <h3 class="summary-title">Cart Totals</h3>

                        <div class="summary-item">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value" id="subtotal"><?= number_format($cartTotal, 2) ?>$</span>
                        </div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shippingButton'])) {
    $parts=explode("-",$_POST['shipping_name']);
    $_SESSION['shipping'] = (float) $parts[0];
    $_SESSION['lastShippingChoise']=$parts[1];
}
$shippingTotal = $_SESSION['shipping'] ?? 0.00;
?>
                        <div class="summary-item">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-value" id="shipping"><?php 
                            if($cartTotal>100)
                            {
                            echo number_format(0, 2);
                            }
                            else {
                            echo number_format($shippingTotal, 2);
                            }
                            ?>$</span>
                        </div>


                        <div class="summary-item">
                            <span class="summary-label">Discount</span>
                            <span class="summary-value text-danger" id="discount"><?= isset($_SESSION['couponTotal'])?number_format($_SESSION['couponTotal'],2):number_format(0,2) ?>$</span>
                        </div>

                        <div class="summary-total">
                            <span>Total</span>
                            <?php 
                            if($cartTotal>100 )
                            {
                        if(isset($_SESSION['couponTotal']))
                            $total=$cartTotal-$_SESSION['couponTotal']; 
                            }
                            else {
                        if(isset($_SESSION['couponTotal']))        
                            $total=$cartTotal+$shippingTotal-$_SESSION['couponTotal']; 
                            }
                            ?>
                            <span class="total-price" id="cartTotal"><?= number_format($total,2)??number_format(0,2) ?>$</span>
                        </div>
                        <div class="payment-methods1 mt-4">
                            <h5 class="mb-3 fw-semibold">Payment Method</h5>

                            <div class="payment-option1">
                                <input type="radio" name="payment" id="card" value="card" checked>
                                <label for="card">
                                    <i class="fas fa-credit-card me-2"></i> Credit / Debit Card
                                </label>
                            </div>

                            <div class="payment-option1">
                                <input type="radio" name="payment" value="paypal" id="paypal">
                                <label for="paypal">
                                    <i class="fab fa-paypal me-2"></i> PayPal
                                </label>
                            </div>

                            <div class="payment-option1">
                                <input type="radio" name="payment" value="cod" id="cod">
                                <label for="cod">
                                    <i class="fas fa-money-bill-wave me-2"></i> Cash on Delivery
                                </label>
                            </div>
                        </div>

                        <button class="btn-checkout" id="checkoutButton">
                            <i class="fas fa-lock me-2"></i> Proceed to Checkout
                        </button>

                        <div class="mt-4 text-center">
                            <p class="text-muted small">Free shipping on orders over $100</p>
                            <a href="products.php" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Shipping Calculator -->
                    <form method="post">
                    <div class="shipping-section mt-4 fade-in">
                        <h4 class="shipping-title">Calculate Shipping</h4>
                        <p class="text-muted small mb-3">Enter your destination to get shipping estimates</p>
                        <select name="shipping_name" class="form-select" id="country">
                            <?php
                            foreach($shipping_discound as $key=>$value)
                            {
                                $select = '';
                                if (isset($_SESSION['lastShippingChoise'])) {
                                $select = ($_SESSION['lastShippingChoise'] == $key) ? 'selected' : '';
                                }
                                echo "<option value='$value-$key' $select>$key</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="shippingButton" class="btn-update w-100 mt-2">
                            <i class="fas fa-calculator me-2"></i> Calculate Shipping
                        </button>
                    </div>
                    </form>
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

    <!-- Back to top -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Quantity button listeners
    document.querySelectorAll('.qty-increase').forEach(button => {
        button.addEventListener('click', function () {
            const qtyInput = this.closest('.quantity-selector').querySelector('.qty-input');
            qtyInput.value = parseInt(qtyInput.value) + 1;
        });
    });

    document.querySelectorAll('.qty-decrease').forEach(button => {
        button.addEventListener('click', function () {
            const qtyInput = this.closest('.quantity-selector').querySelector('.qty-input');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        });
    });
});

    </script>
    <!-- hasan backend -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('applyCoupon');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();

        const coupon = document.getElementById('couponCode').value.trim();
        if (coupon === '') return;

        fetch('cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'coupon=' + encodeURIComponent(coupon)
        })
        .then(res => res.text())
        .then(discount => {
            discount = parseFloat(discount);
            
            document.getElementById('discount').innerText =
                discount > 0 ? discount.toFixed(2) + '$' : '0.00$';
        });
    });

});
// ------- 2 remove 
// sho 3melet 3m t2ol eh ?? awl shi jebet kel buttonet lesm fi item-remove .. ba3den 3melet listeners
// 3al click hynfz lfunction lsh7lta tjble mn awl div bat ela lhwe cart-item lb2lbo qty input 
// kel qty input fi hek q[1] qty[2] .. hon 1 w 2 .. hene l id f 3melna regular 3lya l2o2dr osl ll 1 bs bala klmet qty
// wslt ll id ? yes now b3to k get link :P 
document.querySelectorAll('.item-remove').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.closest('.cart-item').querySelector('.qty-input').name.match(/\d+/)[0];
        window.location.href = '?remove=' + itemId;
    });
});
// to checkout 
document.addEventListener('DOMContentLoaded', function () {
    const checkoutBtn = document.getElementById('checkoutButton');
    if (!checkoutBtn) return;

    checkoutBtn.addEventListener('click', function () {
        // name of payment 
        const paymentMethod = document.querySelector('input[name="payment"]:checked').value;

        const cartTotal = <?= $cartTotal ?>;
        const shippingTotal = <?= $_SESSION['shipping'] ?? 0 ?>;
        const discountTotal = <?= $_SESSION['couponTotal'] ?? 0 ?>;
        const cartItems = <?= json_encode($_SESSION['cart']) ?>;
        // redirect to cart.php with content and encoding as json file 
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'checkout=1&paymentMethod=' + encodeURIComponent(paymentMethod) +
                '&cart=' + encodeURIComponent(JSON.stringify(cartItems)) +
                '&cartTotal=' + cartTotal +
                '&shippingTotal=' + shippingTotal +
                '&discountTotal=' + discountTotal
        })
        // here if echo sucsessin php redirect to my account else error alert
        .then(res => res.text())
        .then(res => {
            if(res === 'success'){
                alert('Order placed successfully!');
                window.location.href = 'myAccount.php';
            } else {
                alert('Error placing order!');
            }
        });
    });
});

</script>
    </body>
</html>
