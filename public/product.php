<!DOCTYPE html>
<html lang="en">

<?php
    session_start();
    include 'db.php';
if(!isset($_SESSION["cart"]))
{
    $_SESSION["cart"]=[];
}

// Handle cart item removal
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
// hone index 0 1 .. 
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // for no overlap with remove delete the remove get
$params = $_GET;
unset($params['remove'], $params['add'], $params['addToCart']);
// http_build_query ha hatyto lyhwel shkel lstring l shkel string mtl url => ?x=10&y=1 ..
$query = http_build_query($params);
// hon check fi ken 3ena get 8er lremove ?? eza eh hotn eza la hot '' ftrd fi x btsr ?x
$url = $_SERVER['PHP_SELF'] . ($query ? '?' . $query : '');

header("Location: $url");
exit;
}


if (isset($_GET['add']) && $_GET['add'] == 1)
{
    $name   =   $_GET['name'];
    $id     =   $_GET['id'];
    $price  =   $_GET['price'];
    $qty    =   $_GET['qty'];
    $photo  = $_GET['photo'];
    $color = $_GET['color'];
    $size = $_GET['size'];
    
    $found=false;
    //& to change the session by variable 
    foreach($_SESSION["cart"] as &$v)
    {
        if($v['id'] == $id)
        {
            $v['qty']+=$qty;
            $found=true;
            break;
        }
    }
    if($found==false)
    {
        $_SESSION["cart"][]=
        [
            'id'=>$id,
            'name'  =>  $name,
            'price' =>  $price,
            'qty'   => $qty,
            'photo' =>   $photo,
            'color' => $color , 
            'size' => $size 
        ];
    }
    $params = $_GET;
    // le hatt add w addtocart mshen m kn 3mel remove ba3ed ladd m yrj3 usr overload tzid kmeye w ana bde emhe :)
unset($params['add'], $params['addToCart'], $params['qty']);

$query = http_build_query($params);
header("Location: product.php" . ($query ? "?$query" : ""));
exit;
}


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Esem el product Product Detail</title>
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">
    
    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        /* Global Styles */
        body {
            background-color: #fff;
        }

        /* Border Visibility */
        .border-bottom,
        .border-top,
        .border,
        .border-color,
        input,
        select,
        textarea,
        .btn {
            border-color: #ddd !important;
        }

        .custom-select,
        .qty-input,
        .review-form input,
        .review-form textarea {
            border: 1px solid #ddd !important;
        }

        .nav-tabs {
            border-bottom: 2px solid #ddd !important;
        }

        .product-tabs {
            border: 1px solid #ddd !important;
        }

        .option-group {
            border-bottom: 1px solid #ddd !important;
        }

        .social-sharing {
            border-top: 1px solid #ddd !important;
        }

        /* Breadcrumb */
        .breadcrumb-container {
            background-color: var(--bg-light);
            padding: 20px 0;
        }

        .bread-crumb {
            display: flex;
            align-items: center;
        }

        .bread-crumb a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
        }

        .bread-crumb a:hover {
            color: var(--primary-color);
        }

        .bread-crumb span {
            color: var(--text-light);
            font-size: 14px;
        }

        .bread-crumb i {
            margin: 0 10px;
            color: #999;
        }

        /* Product Detail */
        .sec-product-detail {
            padding: 60px 0;
        }

        .product-images {
            position: relative;
        }

        .main-product-img {
            width: 80%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid #ddd !important;
        }

        .main-product-img img {
            width: 100%;
            height: 550px;
            object-fit: cover;
            transition: var(--transition);
        }

        .main-product-img:hover img {
            transform: scale(1.02);
        }

        .img-zoom-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            border: 1px solid #ddd !important;
        }

        .img-zoom-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        /* Product Info */
        .product-info h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .product-description {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        /* Product Options */
        .option-group {
            margin-bottom: 25px;
            padding-bottom: 25px;
        }

        .option-label {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .custom-select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            background-color: white;
            font-size: 1rem;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
            outline: none;
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
        }

        .qty-btn {
            width: 45px;
            height: 45px;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
            border: 1px solid #ddd !important;
        }

        .qty-btn:hover {
            background: var(--primary-color);
            color: black;
            border-color: var(--primary-color);
        }

        .qty-input {
            width: 70px;
            height: 45px;
            text-align: center;
            border-left: none;
            border-right: none;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .add-to-cart-btn {
            background: var(--primary-color);
            color: white;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
            margin-left: 20px;
            border: 1px solid transparent !important;
        }

        .add-to-cart-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        /* Social Sharing */
        .social-sharing {
            display: flex;
            align-items: center;
            margin-top: 40px;
            padding-top: 30px;
        }

        .wishlist-btn {
            margin-right: 20px;
            color: var(--text-dark);
            font-size: 1.3rem;
            transition: var(--transition);
        }

        .wishlist-btn:hover {
            color: #e74c3c;
            transform: scale(1.1);
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            background: var(--bg-light);
            /*border-radius: 50%;*/
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
            /*border: 1px solid #ddd !important;*/
        }

        .social-icons a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        /* Product Tabs */
        .product-tabs {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-top: 60px;
        }

        .nav-tabs {
            margin-bottom: 20px;
        }

        .nav-tabs .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 15px 25px;
            background: transparent;
            position: relative;
            border: none !important;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-color);
        }

        .tab-content {
            padding: 30px 0;
        }

        /* Related Products */
        .sec-relate-product {
            padding: 60px 0;
            background: var(--bg-light);
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h3 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 2.2rem;
            position: relative;
            display: inline-block;
        }

        .section-title h3::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Product Grid */
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

        /* Quantity Controls */
        .quantity-selector {
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .qty-btn {
            padding: 10px 14px;
            cursor: pointer;
            background: #f0f0f0;
            width: 60px;
        }

        .qty-btn i {
            font-size: 14px;
        }

        .qty-input {
            width: 120px;
            text-align: center;
            border: none;
            outline: none;
            font-size: 16px;
            background: white;
        }

        /* Add to Cart Button */
        .add-to-cart-btn {
            padding: 12px 25px;
            background: #1e88e5;
            color: white;
            border: 2px solid #1565c0;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: 0.2s ease-in-out;
        }

        .add-to-cart-btn i {
            font-size: 18px;
        }

        .add-to-cart-btn:hover {
            background: #1565c0;
            border-color: #0d47a1;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px 20px;
            align-items: center;
            margin-top: 20px;
        }

        .form-row {
            display: contents;
        }

        .form-row label {
            font-weight: 600;
        }

        .custom-input {
            width: 200px;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        /* Quantity Smaller */
        .small-qty {
            transform: scale(0.85);
            transform-origin: left;
        }

        .small-btn {
            width: 150px;
            padding: 8px 12px;
            border-radius: 6px;
        }

        /* Product Tabs Wrapper */
        .product-tabs-wrapper {
            width: 50%;
            margin: 0 auto;
        }

        .product-tabs .nav-tabs {
            justify-content: center;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .product-info h1 {
                font-size: 2rem;
            }
            
            .main-product-img img {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .add-to-cart-btn {
                margin-left: 0;
                margin-top: 20px;
                width: 100%;
            }
            
            .quantity-selector {
                justify-content: center;
            }
            
            .product-tabs-wrapper {
                width: 100%;
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

        /* Image Zoom Modal Styles */
.image-zoom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    background: rgba(0, 0, 0, 0.9);
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-zoom-modal.active {
    display: flex;
    opacity: 1;
}

.zoom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: zoom-out;
}

.zoom-modal-content {
    position: relative;
    background: #fff;
    border-radius: 10px;
    max-width: 90%;
    max-height: 90%;
    width: 90%;
    height: 90%;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transform: scale(0.95);
    transition: transform 0.3s ease;
}

.image-zoom-modal.active .zoom-modal-content {
    transform: scale(1);
}

.zoom-modal-header {
    display: flex;
    justify-content: flex-end;
    padding: 15px;
    background: #fff;
    border-bottom: 1px solid #eee;
}

.zoom-close-btn,
.zoom-fullscreen-btn {
    background: none;
    border: none;
    color: #333;
    font-size: 20px;
    cursor: pointer;
    margin-left: 10px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.zoom-close-btn:hover {
    background: #ff4444;
    color: white;
}

.zoom-fullscreen-btn:hover {
    background: #4CAF50;
    color: white;
}

.zoom-image-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 20px;
    background: #f8f8f8;
}

#zoomedImage {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    cursor: grab;
    transition: transform 0.2s ease;
}

#zoomedImage:active {
    cursor: grabbing;
}

.zoom-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    background: #fff;
    border-top: 1px solid #eee;
    gap: 15px;
}

.zoom-control-btn {
    background: #f0f0f0;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.zoom-control-btn:hover {
    background: #4CAF50;
    color: white;
}

.zoom-slider {
    width: 200px;
    height: 6px;
    -webkit-appearance: none;
    appearance: none;
    background: #ddd;
    border-radius: 3px;
    outline: none;
}

.zoom-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #4CAF50;
    cursor: pointer;
}

.zoom-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #4CAF50;
    cursor: pointer;
    border: none;
}

.zoom-percentage {
    min-width: 50px;
    text-align: center;
    font-weight: 600;
    color: #333;
}

/* Make main image clickable */
.main-product-img {
    cursor: pointer;
}
/* hasan jak */
.display-content-form{
    display: contents;
}
.main-product-img:hover {
    opacity: 0.95;
}
    </style>
</head>

<body>
    

    <!-- Top Promo Bar -->
    <div class="top-promo-bar">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="promo-text mb-2 mb-md-0">
                    Free shipping for standard order over $100
                </div>
                <div class="right-links d-flex">
                    <a href="#" class="me-3">Help & FAQs</a>
                    <a href="#" class="me-3">My Account</a>
                    <a href="login.php" class="me-3">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-light">
        <div class="container">
            <span class="logo md-0"><b>Moda</b>X</span>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
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

    <!-- Cart Sidebar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="cartSidebarLabel">Your Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="cart-items-container" style="max-height: 400px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    <!-- hasan + haidar backend and front end  -->
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
                                <img src="assets/images/<?= htmlspecialchars($item['photo']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="col-9">
                                <a href="#" class="text-decoration-none text-dark fw-semibold d-block mb-1">
                                    <?= htmlspecialchars($item['name']) ?>
                                </a>
                                <span class="text-muted small">
                                    <?= htmlspecialchars($item['qty']) ?> x $<?= number_format($item['price'], 2) ?>
                                </span>
                                <!-- why use this ? to dont forget the get reload page with all 
                                get only add the remove to get-->
                                <a href="<?= $_SERVER['REQUEST_URI'] ?>&remove=<?= $item['id'] ?>"
                        class="btn btn-sm btn-outline-danger mt-1">
                            <i class="fas fa-trash"></i>
                                    </a>
                            </div>
                        </div>
                    </li>
                    <?php
                        endforeach;
                    else:
                    ?>
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

    <!-- Product Detail -->
    <section class="sec-product-detail">
        <div class="container">
            <div class="row">
                <!-- Product Images -->
<div class="col-lg-6">
    <div class="product-images">
        <div class="main-product-img" id="mainProductContainer">
            <img id="mainProductImage" src="assets/images/<?php if(isset($_GET["photo"])){echo $_GET["photo"];}
                else {
                    echo "";
                }
                ?>" alt="<?php if(isset($_GET["name"])){echo $_GET["name"];} ?>" 
                onclick="openImageZoom()">
            <a href="#" class="img-zoom-btn" id="zoomButton" onclick="openImageZoom(); return false;">
                <i class="fa fa-expand"></i>
            </a>
        </div>
    </div>
</div>

<!-- Image Zoom Modal -->
<div class="image-zoom-modal" id="imageZoomModal">
    <div class="zoom-modal-overlay" onclick="closeImageZoom()"></div>
    <div class="zoom-modal-content">
        <div class="zoom-modal-header">
            <button class="zoom-close-btn" onclick="closeImageZoom()">
                <i class="fas fa-times"></i>
            </button>
            <button class="zoom-fullscreen-btn" onclick="toggleFullScreen()">
                <i class="fas fa-expand"></i>
            </button>
        </div>
        <div class="zoom-image-container">
            <img id="zoomedImage" src="assets/images/product-01.jpg" alt="Lightweight Jacket - Zoomed">
        </div>
        <div class="zoom-controls">
            <button class="zoom-control-btn" onclick="zoomOut()">
                <i class="fas fa-search-minus"></i>
            </button>
            <input type="range" min="100" max="500" value="100" class="zoom-slider" 
                   id="zoomSlider" oninput="updateZoom(this.value)">
            <button class="zoom-control-btn" onclick="zoomIn()">
                <i class="fas fa-search-plus"></i>
            </button>
            <span class="zoom-percentage">100%</span>
        </div>
    </div>
</div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <h1><?= $_GET["name"] ?? "Guest" ?></h1>
                        <div class="product-price"><?= $_GET["price"]?? "0" ?>$</div>

                        <div class="form-grid">
                            <!-- Size Selection -->
                            
                            <!-- can use with the Post but its ok :P -->
                            <form method="GET" class="display-content-form">

                            <div class="form-row">
                                <label for="size-select">Size</label>
                                <select name="size" id="size-select" class="custom-input">
                                    <option value="">Choose an option</option>
                                    <option value="s">Size S</option>
                                    <option value="m">Size M</option>
                                    <option value="l">Size L</option>
                                    <option value="xl">Size XL</option>
                                </select>
                            </div>

                            <!-- Color Selection -->
                            <div class="form-row">
                                <label for="color-select">Color</label>
                                <select name="color" id="color-select" class="custom-input">
                                    <option value="">Choose an option</option>
                                    <option value="red">Red</option>
                                    <option value="blue">Blue</option>
                                    <option value="white">White</option>
                                    <option value="grey">Grey</option>
                                </select>
                            </div>



                            <input type="hidden" name="id" value="<?= $_GET["id"]??"" ?>">
                            <input type="hidden" name="name" value="<?= $_GET["name"]??"" ?>">
                            <input type="hidden" name="price" value="<?= $_GET["price"]??"" ?>">
                            <input type="hidden" name="photo" value="<?= $_GET["photo"]??"" ?>">
                            <input type="hidden" name="add" value="1">
                            <!-- Quantity Selection -->
                            <div class="form-row">
                                <label>Quantity</label>
                                <div class="quantity-selector small-qty">
                                    <button type="button" class="qty-btn" onclick="decreaseQuantity()">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input class="qty-input" name="qty" type="number" value="1" id="quantity" min="1">
                                    <button type="button" class="qty-btn" onclick="increaseQuantity()">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            
                            
                            <!-- Add to Cart -->
                            <div class="form-row">
                                <label></label>
                                <button type="submit" name="addToCart" class="add-to-cart-btn small-btn" onclick="addToCart()">
                                    <i class="fa fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Product Tabs -->
        <div class="product-tabs-wrapper">
            <div class="product-tabs">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="information-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab">Additional Information</button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabContent">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <p class="mt-4">
                            <?php
                            if(isset($_GET["name"]) && isset($_GET["type"])){
                            $stmt=$connect->prepare("select description from products 
                            where name = ? AND type = ? ");
                            $stmt->bind_param("ss",$_GET["name"],$_GET["type"]);
                            $stmt->execute();
                            $result=$stmt->get_result();
                            if($result->num_rows==1)
                            {
                                $row=$result->fetch_assoc();
                                echo $row["description"];
                            }
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Information Tab -->
                    <div class="tab-pane fade" id="information" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-between py-3 border-bottom">
                                        <span class="fw-bold">Materials</span>
                                        <span>60% cotton, 40% polyester</span>
                                    </li>
                                    <li class="d-flex justify-content-between py-3 border-bottom">
                                        <span class="fw-bold">Color</span>
                                        <span>Black, Blue, Grey, Green, Red, White</span>
                                    </li>
                                    <li class="d-flex justify-content-between py-3">
                                        <span class="fw-bold">Size</span>
                                        <span>XL, L, M, S</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr>

    <!-- Related Products -->
    <div class="container">
        <div class="section-title mt-5">
            <h3>Related Products</h3>
        </div>
        <div class="row" id="product-grid">
            
        <!-- Hasan data base query -->
        <?php
        $stmt=$connect->prepare("SELECT p.category_id,p.id,p.price, p.name, i.filename, p.type
                        FROM products AS p
                        JOIN product_images AS i ON p.id = i.product_id
                        JOIN categories AS c ON c.id = p.category_id
                        WHERE p.category_id = ? 
                        AND p.type = ?
                        AND p.id != ?"); // la5era mshen m y3rdle nfs product mra tenye
        
$stmt->bind_param("isi", $_GET["category"],$_GET["type"],$_GET["id"]);
$stmt->execute();
$result=$stmt->get_result();
        ?>
        <?php
        if ($result->num_rows>0)
            {
                while($row=$result->fetch_assoc())
                {
                    ?>
            <!-- Product i -->
            <div class="col-sm-6 col-md-4 col-lg-3 product-item <?= $row["type"] ?>">
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/<?= $row["filename"] ?>" alt="<?= $row["name"] ?>">
                        <a href="product.php?name=<?= $row['name'] ?>&price=
                <?= $row['price'] ?>&photo=<?= $row['filename'] ?>&type=<?= $row['type'] ?>
                &id=<?= $row['id'] ?> &category=<?= $row['category_id'] ?>" class="quick-view-btn">Quick View</a>
                    </div>
                    <div class="product-info d-flex justify-content-between align-items-start">
                        <div>
                            <a href="#" class="product-name"><?= $row["name"] ?></a>
                            <div class="product-price"><?= $row["price"] ?></div>
                        </div>
                        <button class="wishlist-btn">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php 
                }
            }?>
        </div>
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

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Quantity Control Functions
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }

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
        // Image Zoom Functionality
let isDragging = false;
let startX, startY, translateX = 0, translateY = 0;
let currentZoom = 100;
let isFullScreen = false;

function openImageZoom() {
    const modal = document.getElementById('imageZoomModal');
    const zoomedImage = document.getElementById('zoomedImage');
    const mainImage = document.getElementById('mainProductImage');
    
    // Set the zoomed image source
    zoomedImage.src = mainImage.src;
    
    // Reset zoom and position
    currentZoom = 100;
    translateX = 0;
    translateY = 0;
    updateZoom(100);
    
    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeImageZoom() {
    const modal = document.getElementById('imageZoomModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    
    // Exit fullscreen if active
    if (isFullScreen && document.fullscreenElement) {
        document.exitFullscreen();
        isFullScreen = false;
    }
}

function toggleFullScreen() {
    const modalContent = document.querySelector('.zoom-modal-content');
    
    if (!isFullScreen) {
        if (modalContent.requestFullscreen) {
            modalContent.requestFullscreen();
        } else if (modalContent.webkitRequestFullscreen) {
            modalContent.webkitRequestFullscreen();
        } else if (modalContent.msRequestFullscreen) {
            modalContent.msRequestFullscreen();
        }
        isFullScreen = true;
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        isFullScreen = false;
    }
}

function zoomIn() {
    if (currentZoom < 500) {
        currentZoom += 25;
        updateZoom(currentZoom);
    }
}

function zoomOut() {
    if (currentZoom > 100) {
        currentZoom -= 25;
        updateZoom(currentZoom);
    }
}

function updateZoom(value) {
    const zoomedImage = document.getElementById('zoomedImage');
    const zoomSlider = document.getElementById('zoomSlider');
    const zoomPercentage = document.querySelector('.zoom-percentage');
    
    currentZoom = parseInt(value);
    zoomedImage.style.transform = `scale(${currentZoom / 100}) translate(${translateX}px, ${translateY}px)`;
    zoomSlider.value = currentZoom;
    zoomPercentage.textContent = `${currentZoom}%`;
}

// Pan/Drag functionality
document.getElementById('zoomedImage').addEventListener('mousedown', startDrag);
document.getElementById('zoomedImage').addEventListener('touchstart', startDragTouch);

function startDrag(e) {
    if (currentZoom <= 100) return;
    
    isDragging = true;
    startX = e.clientX - translateX;
    startY = e.clientY - translateY;
    
    e.preventDefault();
}

function startDragTouch(e) {
    if (currentZoom <= 100) return;
    
    isDragging = true;
    const touch = e.touches[0];
    startX = touch.clientX - translateX;
    startY = touch.clientY - translateY;
    
    e.preventDefault();
}

document.addEventListener('mousemove', drag);
document.addEventListener('touchmove', dragTouch);

function drag(e) {
    if (!isDragging) return;
    
    translateX = e.clientX - startX;
    translateY = e.clientY - startY;
    
    updateZoom(currentZoom);
}

function dragTouch(e) {
    if (!isDragging) return;
    
    const touch = e.touches[0];
    translateX = touch.clientX - startX;
    translateY = touch.clientY - startY;
    
    updateZoom(currentZoom);
}

document.addEventListener('mouseup', stopDrag);
document.addEventListener('touchend', stopDrag);

function stopDrag() {
    isDragging = false;
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageZoom();
    }
});

// Handle wheel zoom
document.getElementById('zoomedImage').addEventListener('wheel', function(e) {
    e.preventDefault();
    
    if (e.deltaY < 0) {
        // Scroll up - zoom in
        zoomIn();
    } else {
        // Scroll down - zoom out
        zoomOut();
    }
}, { passive: false });

// Reset zoom when image is clicked at 100%
document.getElementById('zoomedImage').addEventListener('click', function(e) {
    if (currentZoom === 100) {
        closeImageZoom();
    }
});

// Fullscreen change event
document.addEventListener('fullscreenchange', handleFullscreenChange);
document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
document.addEventListener('msfullscreenchange', handleFullscreenChange);

function handleFullscreenChange() {
    isFullScreen = !isFullScreen;
}
// Keyboard shortcuts for better UX
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageZoomModal');
    
    if (!modal.classList.contains('active')) return;
    
    switch(e.key) {
        case '+':
        case '=':
            e.preventDefault();
            zoomIn();
            break;
        case '-':
            e.preventDefault();
            zoomOut();
            break;
        case '0':
            e.preventDefault();
            updateZoom(100);
            break;
        case 'ArrowLeft':
        case 'ArrowRight':
        case 'ArrowUp':
        case 'ArrowDown':
            if (currentZoom > 100) {
                e.preventDefault();
                const step = 50;
                switch(e.key) {
                    case 'ArrowLeft':
                        translateX -= step;
                        break;
                    case 'ArrowRight':
                        translateX += step;
                        break;
                    case 'ArrowUp':
                        translateY -= step;
                        break;
                    case 'ArrowDown':
                        translateY += step;
                        break;
                }
                updateZoom(currentZoom);
            }
            break;
    }
});
    </script>
</body>

</html>



