<?php
session_start();
include '../db.php';
    $admin_id=$_SESSION['admin_id'] ?? '';
    $admin_name=$_SESSION['admin_name'] ?? ""; 
    if(!isset($admin_id)){header("Location:login.php");}
    $stats=[];

    function update_notify($connect , $id)
    {
        $stmt=$connect->prepare("UPDATE stock_notifications set notified = 1 where id = $id");
        $stmt->execute();
        $stmt->close();
    }




    //total products
    $stmt = $connect->query("SELECT COUNT(*) AS total_products FROM products");
    $stats['total_products'] = $stmt->fetch_assoc()['total_products'];

    //total orders
    $stmt=$connect->query("SELECT COUNT(*) AS total_orders FROM orders");
    $stats['total_orders']=$stmt->fetch_assoc()['total_orders'];

    //total customers
    $stmt=$connect->query("SELECT COUNT(*) AS total_customers FROM users");
    $stats['total_customers']=$stmt->fetch_assoc()['total_customers'];

    //total revenue
    $stmt=$connect->query("SELECT SUM(total) AS total FROM orders WHERE status='completed' or status= 'processing'");
    $revenue=$stmt->fetch_assoc()['total'];
    $stats['total']=$revenue ? number_format($revenue,2) : '0.00';

    //inventory stats
    $stmt = $connect->query("SELECT 
        SUM(CASE WHEN stock > 10 THEN 1 ELSE 0 END) AS in_stock,
        SUM(CASE WHEN stock <= 10 AND stock > 0 THEN 1 ELSE 0 END) AS low_stock,
        SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) AS out_of_stock
        FROM products");
    $inventory_stats = $stmt->fetch_assoc();

    //fetch top products
    $stmt = $connect->query("
    SELECT p.* , c.name as category_name, pi.filename
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
    WHERE p.stock > 0 
    ORDER BY p.created_at DESC
    LIMIT 6
    ");
    $top_products = $stmt->fetch_all(MYSQLI_ASSOC);

    //fetch all produvcts for management
    $all_products=[];
    $stmt = $connect->query("
    SELECT p.* , c.name as category_name, pi.filename
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
    ORDER BY p.name DESC
    ");
    $all_products = $stmt->fetch_all(MYSQLI_ASSOC);;


    // Fetch categories for filter
    $stmt = $connect->query("SELECT * FROM categories WHERE name IN ('Products', 'Banners', 'Gallery')");
    $categories = $stmt->fetch_all(MYSQLI_ASSOC);

    //fetch customers
    $stmt =$connect->query("SELECT  * FROM users ORDER BY created_at DESC LIMIT 5");
    $customers=$stmt->fetch_all(MYSQLI_ASSOC);;

    //handle add product
    if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_product'])){
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $stock = $_POST['stock'];
        $sale_price = $_POST['sale_price'];
        $type=$_POST['type'];
        $color = $_POST['color'];

        $stmt = $connect->prepare("INSERT INTO products (category_id, name, description, price, sale_price, stock, type, color)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $category_id,
            $name,
            $description,
            $price,
            $sale_price,
            $stock,
            $type,
            $color
        ]);

        $product_id = $connect->insert_id;

        //handle image upload
        if(isset($_FILES['product_image'])&& $_FILES['product_image']['error']===0){
            $target_dir="../assets/images/";
            if(!is_dir($target_dir)){
                mkdir($target_dir,0777,true);
            }

            $filename = time() . '_' . basename($_FILES['product_image']['name']);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                //insert into product_images
                $stmt = $connect->prepare("INSERT INTO product_images (product_id, filename, is_main) VALUES (?, ?, 1)");
                
                $stmt->execute([$product_id, $filename]);
        }
    }

        header("Location: index.php?section=products&msg=added");
        exit();
    }

    //handle delete product
    if(isset($_GET['delete_product'])){
        $product_id = intval($_GET['delete_product']);


        //delete product images
        $stmt = $connect->prepare("DELETE FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);

        //delete product
        $stmt = $connect->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        header("Location: index.php?section=products&msg=deleted");
        exit();
    }

    //handle update quantity
    if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['update_quantity'])){
        $product_id = intval($_POST['product_id']);
        $newqty = intval($_POST['quantity']);
        $stmt = $connect->prepare("SELECT stock from products where id = ?");
        $stmt->bind_param("i",$product_id);
        $stmt->execute();
        $stmt->bind_result($oldqty);
        $stmt->close();
        if($oldqty==0 && $newqty > 0)
        {
            $stmt=$connect->prepare("SELECT id,email FROM stock_notifications where notified = 0 and product_id=$product_id ");
            $stmt->execute();
            $emails = $stmt->get_result();
            while($row = $emails->fetch_assoc())
            {
                mail($row['email'],"Stock Updated !!!","the product you requested is available ");
                update_notify($connect,$row['id']);
            }
            $stmt->close();
        }
            $stmt = $connect->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmt->execute([$newqty, $product_id]);
            $stmt->close();
            header("Location: index.php?section=products&msg=quantity_updated");
            exit();
        }

    //handle logout
    if (isset($_GET['logout'])){
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();
    }

    //determine active section
    $active_section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

    ?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            
            --primary-dark: #0b5ed7;
            --secondary-color: #3498db;
            --accent-color: #9b59b6;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --text-dark: #333;
            --text-light: #777;
            --bg-light: #f8f9fa;
            --border-color: #e9ecef;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
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

        .admin-profile {
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }
        .admin-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .admin-role {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-link {
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
        }

        .menu-link:hover,
        .menu-link.active {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .menu-link i {
            width: 25px;
            margin-right: 15px;
            font-size: 1.2rem;
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
            border-top: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-card.products {
            border-top-color: var(--primary-color);
        }

        .stat-card.orders {
            border-top-color: var(--secondary-color);
        }

        .stat-card.customers {
            border-top-color: var(--accent-color);
        }

        .stat-card.revenue {
            border-top-color: var(--success-color);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.5rem;
            color: white;
        }

        .products .stat-icon {
            background: var(--primary-color);
        }

        .orders .stat-icon {
            background: var(--secondary-color);
        }

        .customers .stat-icon {
            background: var(--accent-color);
        }

        .revenue .stat-icon {
            background: var(--success-color);
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

        /* Products Management Card */
        .products-card {
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

        /* Tabs for Product Management */
        .product-tabs {
            display: flex;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 25px;
            overflow-x: auto;
        }

        .product-tab {
            padding: 12px 25px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .product-tab:hover {
            color: var(--primary-color);
        }

        .product-tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        /* Product List */
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .product-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
        }

        .product-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .product-image-container {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .product-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-item:hover .product-image-container img {
            transform: scale(1.05);
        }

        .availability-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .in-stock {
            background: rgba(46, 204, 113, 0.15);
            color: #27ae60;
        }

        .low-stock {
            background: rgba(243, 156, 18, 0.15);
            color: var(--warning-color);
        }

        .out-of-stock {
            background: rgba(231, 76, 60, 0.15);
            color: var(--primary-color);
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .product-category {
            color: var(--primary-color);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .product-description {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
            height: 42px;
            overflow: hidden;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-price .old-price {
            font-size: 0.9rem;
            color: var(--text-light);
            text-decoration: line-through;
            margin-right: 5px;
        }

        .product-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .spec-badge {
            padding: 4px 10px;
            background: #f8f9fa;
            border-radius: 15px;
            font-size: 0.8rem;
            color: var(--text-dark);
        }

        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Customer Info */
        .customer-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }

        .customer-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .customer-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }

        .customer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .customer-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .customer-email {
            color: var(--text-light);
            font-size: 0.95rem;
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

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .bg-success {
            background-color: rgba(46, 204, 113, 0.15) !important;
            color: #27ae60 !important;
        }

        .bg-warning {
            background-color: rgba(243, 156, 18, 0.15) !important;
            color: var(--warning-color) !important;
        }

        .bg-danger {
            background-color: rgba(231, 76, 60, 0.15) !important;
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: rgba(52, 152, 219, 0.15) !important;
            color: var(--secondary-color) !important;
        }

        /* Form Styling */
        .add-product-form {
            max-width: 900px;
            margin: 0 auto;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        /* Table Styling */
        .quantity-table-container {
            overflow-x: auto;
        }

        .quantity-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .quantity-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--primary-color);
        }

        .quantity-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .quantity-table tr:hover {
            background: #f8f9fa;
        }

        /* Tab Content */
        .product-tab-content {
            display: none;
        }

        .product-tab-content.active {
            display: block;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-label {
            color: var(--text-light);
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-sidebar {
                position: static;
                margin-bottom: 30px;
            }

            .product-list {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .product-list {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.6rem;
            }

            .stats-grid .col-6 {
                margin-bottom: 20px;
            }

            .products-card,
            .customer-card,
            .signout-card {
                padding: 20px;
            }

            .stat-number {
                font-size: 2rem;
            }

            .product-list {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .product-actions {
                flex-direction: column;
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
                    
                    <a href="#" class="me-3">My Account</a>
                    
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
                </ul>

                
            </div>
        </div>
    </nav>

    <!-- dashboard content-->
     <section class="dashboard-container">
        <div class="container">
            <div class="row">
                <!--sidebar-->
                <div class="col-lg-3">
                    <div class="dashboard-sidebar">
                        <div class="sidebar-card">
                            <div class="admin-profile">
                                <h3 class="admin-name"><?php echo htmlspecialchars($admin_name); ?></h3>
                                <div class="admin-role">Store Administrator</div>
                                <p class="mt-3" style="font-size: 0.9rem; opacity: 0.9;">Admin ID: ADM-<?php echo str_pad($admin_id, 5, '0', STR_PAD_LEFT); ?></p>
                            </div>

                            <div class="sidebar-menu">
                                <a href="index.php?section=dashboard" class="menu-link <?php echo ($active_section == 'dashboard') ? 'active' : ''; ?>">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="index.php?section=products" class="menu-link <?php echo ($active_section == 'products') ? 'active' : ''; ?>">
                                    <i class="fas fa-tshirt"></i>
                                    <span>Product Management</span>
                                </a>
                                <a href="index.php?section=customers" class="menu-link <?php echo ($active_section == 'customers') ? 'active' : ''; ?>">
                                    <i class="fas fa-users"></i>
                                    <span>Customers</span>
                                </a>
                                <a href="index.php?logout=1" class="menu-link">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Sign Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!--main content-->
                <div class="col-lg-9">
                    <?php
                    if(isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        switch($_GET['msg']){
                            case 'added':
                                echo "Product added successfully.";
                                break;
                            case 'deleted':
                                echo "Product deleted successfully.";
                                break;
                            case 'quantity_updated':
                                echo "Product quantity updated successfully.";
                                break;
                        }?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    <!--dashboard content-->
                    <?php if($active_section=='dashboard'):?>
                        <div id="dashboard">
                            <!--stats cards-->
                            <div class="row stats-grid g-4">
                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card products">
                                    <div class="stat-icon">
                                        <i class="fas fa-tshirt"></i>
                                    </div>
                                    <div class="stat-number"><?php echo $stats['total_products']; ?></div>
                                    <div class="stat-label">Total Products</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card orders">
                                    <div class="stat-icon">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div class="stat-number"><?php echo $stats['total_orders']; ?></div>
                                    <div class="stat-label">Total Orders</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card customers">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-number"><?php echo $stats['total_customers']; ?></div>
                                    <div class="stat-label">Customers</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="stat-card revenue">
                                    <div class="stat-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="stat-number">$<?php echo $stats['total']; ?></div>
                                    <div class="stat-label">Revenue</div>
                                </div>
                            </div>
                        </div>
                        <!-- Inventory Stats -->
                        <div class="row stats-grid g-4">
                            <div class="col-lg-4 col-sm-6">
                                <div class="stat-card" style="border-top-color: #2ecc71;">
                                    <div class="stat-icon" style="background: #2ecc71;">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-number"><?php echo $inventory_stats['in_stock'] ?? 0; ?></div>
                                    <div class="stat-label">In Stock</div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="stat-card" style="border-top-color: #f39c12;">
                                    <div class="stat-icon" style="background: #f39c12;">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="stat-number"><?php echo $inventory_stats['low_stock'] ?? 0; ?></div>
                                    <div class="stat-label">Low Stock</div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="stat-card" style="border-top-color: #e74c3c;">
                                    <div class="stat-icon" style="background: #e74c3c;">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="stat-number"><?php echo $inventory_stats['out_of_stock'] ?? 0; ?></div>
                                    <div class="stat-label">Out of Stock</div>
                                </div>
                            </div>
                        </div>
                        <!-- Top Products -->
                        <div class="products-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="card-title mb-0">Top Products</h3>
                                <span class="badge bg-primary">Updated Today</span>
                            </div>

                            <div class="product-list">
                                <?php foreach ($top_products as $product): ?>
                                <div class="product-item">
                                    <div class="product-image-container">
                                        <img src="<?php echo $product['filename'] ? '../assets/images/' . htmlspecialchars($product['filename']) : 'https://via.placeholder.com/400x300?text=No+Image'; ?>">

                                        <span class="availability-badge <?php 
                                            if ($product['stock'] > 10) echo 'in-stock';
                                            elseif ($product['stock'] > 0) echo 'low-stock';
                                            else echo 'out-of-stock';
                                        ?>">
                                            <?php 
                                            if ($product['stock'] > 10) echo 'In Stock';
                                            elseif ($product['stock'] > 0) echo 'Low Stock';
                                            else echo 'Out of Stock';
                                            ?>
                                        </span>
                                    </div>
                                    <div class="product-info">
                                        <div class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></div>
                                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div class="product-description"><?php echo htmlspecialchars($product['description'] ?? 'No description available'); ?></div>
                                        <div class="product-details">
                                            <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                                        </div>
                                        <div class="product-specs">
                                            <span class="spec-badge">Type: <?php echo htmlspecialchars($product['type']); ?></span>
                                            <?php if ($product['color']): ?>
                                            
                                            <?php endif; ?>
                                            <span class="spec-badge">Qty: <?php echo $product['stock']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Product Management Content -->
                    <?php if ($active_section == 'products'): ?>
                    <div id="products">
                        <div class="products-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="card-title mb-0">Product Inventory Management</h3>
                                <span class="badge bg-primary">Manage your clothing catalog</span>
                            </div>

                            <!-- Product Management Tabs -->
                            <div class="product-tabs">
                                <button type="button" class="product-tab active" data-tab="view-products">
                                    <i class="fas fa-list me-2"></i>View All Products
                                </button>
                                <button type="button" class="product-tab" data-tab="add-product">
                                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                                </button>
                                <button type="button" class="product-tab" data-tab="manage-quantity">
                                    <i class="fas fa-boxes me-2"></i>Manage Inventory
                                </button>
                            </div>

                            <!-- View All Products Tab -->
                            <div id="view-products" class="product-tab-content active">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Product Catalog</h5>
                                    <form method="GET" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="section" value="products">
                                        <select name="category_filter" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                                            <option value="all">All Categories</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category_filter']) && $_GET['category_filter'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>

                                <div class="product-list">
                                    <?php foreach ($all_products as $product): ?>
                                    <div class="product-item">
                                        <div class="product-image-container">
                                            <img src="<?php echo $product['filename'] ? '../assets/images/' . htmlspecialchars($product['filename']) : 'https://via.placeholder.com/400x300?text=No+Image'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            <span class="availability-badge <?php 
                                                if ($product['stock'] > 10) echo 'in-stock';
                                                elseif ($product['stock'] > 0) echo 'low-stock';
                                                else echo 'out-of-stock';
                                            ?>">
                                                <?php 
                                                if ($product['stock'] > 10) echo 'In Stock';
                                                elseif ($product['stock'] > 0) echo 'Low Stock';
                                                else echo 'Out of Stock';
                                                ?>
                                            </span>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></div>
                                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                            <div class="product-description"><?php echo htmlspecialchars($product['description'] ?? 'No description available'); ?></div>
                                            <div class="product-details">
                                                <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                                            </div>
                                            <div class="product-specs">
                                                <span class="spec-badge">Type: <?php echo htmlspecialchars($product['type']); ?></span>
                                                <?php if ($product['color']): ?>
                                                <span class="spec-badge">Color: <?php echo htmlspecialchars($product['color']); ?></span>
                                                <?php endif; ?>
                                                <span class="spec-badge">Qty: <?php echo $product['stock']; ?></span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="index.php?section=products&delete_product=<?php echo $product['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Add New Product Tab -->
                            <div id="add-product" class="product-tab-content">
                                <h5 class="mb-4">Add New Product to Catalog</h5>
                                
                                <form method="POST" enctype="multipart/form-data" class="add-product-form">
                                    <div class="form-section">
                                        <h6 class="form-section-title">Product Information</h6>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="product-name">Product Name *</label>
                                                <input type="text" name="name" id="product-name" class="form-control" placeholder="e.g., Premium Cotton T-Shirt" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="product-category">Category *</label>
                                                <select name="category_id" id="product-category" class="form-control" required>
                                                    <option value="">Select Category</option>
                                                    <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="product-description">Description</label>
                                            <textarea name="description" id="product-description" class="form-control" rows="3" placeholder="Describe the product features, material, and benefits..."></textarea>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="product-type">Type *</label>
                                                <select name="type" id="product-type" class="form-control" required>
                                                    <option value="">Select Type</option>
                                                    <option value="men">Men</option>
                                                    <option value="women">Women</option>
                                                    <option value="kids">Bags</option>
                                                    <option value="shoes">Shoes</option>
                                                    <option value="watches">Watches</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="product-color">Color</label>
                                                <input type="text" name="color" id="product-color" class="form-control" placeholder="e.g., Red, Blue, Black">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pricing & Inventory -->
                                    <div class="form-section">
                                        <h6 class="form-section-title">Pricing & Inventory</h6>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="product-price">Price ($) *</label>
                                                <input type="number" name="price" id="product-price" class="form-control" step="0.01" min="0" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="product-sale-price">Sale Price ($)</label>
                                                <input type="number" name="sale_price" id="product-sale-price" class="form-control" step="0.01" min="0">
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="product-quantity">Initial Quantity *</label>
                                                <input type="number" name="stock" id="product-quantity" class="form-control" min="0" required value="10">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="form-section">
                                        <h6 class="form-section-title">Product Image</h6>
                                        <div class="form-group">
                                            <label for="product-image">Upload Product Image</label>
                                            <input type="file" name="product_image" id="product-image" class="form-control" accept="image/*">
                                            <small class="text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-end mt-4">
                                        <button type="reset" class="btn btn-secondary me-2">Reset Form</button>
                                        <button type="submit" name="add_product" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-2"></i>Add Product to Catalog
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Manage Quantity Tab -->
                            <div id="manage-quantity" class="product-tab-content">
                                <h5 class="mb-4">Update Product Quantities</h5>
                                <p class="text-muted mb-4">Adjust the available quantity for each product in your inventory</p>

                                <div class="quantity-table-container">
                                    <table class="quantity-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Current Stock</th>
                                                <th>Low Stock Alert</th>
                                                <th>Update Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($all_products as $product): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                                                <td>
                                                    <span class="badge <?php 
                                                        if ($product['stock'] > 10) echo 'bg-success';
                                                        elseif ($product['stock'] > 0) echo 'bg-warning';
                                                        else echo 'bg-danger';
                                                    ?>">
                                                        <?php echo $product['stock']; ?> units
                                                    </span>
                                                    <small class="text-muted d-block">
                                                        <?php 
                                                        if ($product['stock'] > 10) echo 'In Stock';
                                                        elseif ($product['stock'] > 0) echo 'Low Stock';
                                                        else echo 'Out of Stock';
                                                        ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">10 units</span>
                                                </td>
                                                <td>
                                                    <form method="POST" class="d-flex align-items-center gap-2">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                        <div class="input-group input-group-sm" style="width: 200px;">
                                                            <input type="number" name="quantity" class="form-control" value="<?php echo $product['stock']; ?>" min="0">
                                                            <button type="submit" name="update_quantity" class="btn btn-outline-primary">
                                                                Update
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>                                
                    
                    <!-- Customers Content -->
                    <?php if ($active_section == 'customers'): ?>
                    <div id="customers">
                        <div class="customer-card">
                            <h3 class="card-title">Customer Management</h3>
                            <p class="text-muted mb-4">View and manage your customer database</p>

                            <div class="row">
                                <?php foreach ($customers as $customer): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="customer-header mb-4">
                                        <div class="customer-avatar">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($customer['name']); ?>&background=e74c3c&color=fff" alt="Customer">
                                        </div>
                                        <div>
                                            <div class="customer-name"><?php echo htmlspecialchars($customer['name']); ?></div>
                                            <div class="customer-email"><?php echo htmlspecialchars($customer['email']); ?></div>
                                            <span class="badge bg-success">Active Customer</span>
                                        </div>
                                    </div>
                                    
                                    <div class="customer-details">
                                        <div class="detail-item">
                                            <div class="detail-label">Phone</div>
                                            <div class="detail-value"><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Member Since</div>
                                            <div class="detail-value"><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
     </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            
            const productTabs = document.querySelectorAll('.product-tab');
            const tabContents = document.querySelectorAll('.product-tab-content');
            
            productTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Update active tab
                    productTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show target content, hide others
                    tabContents.forEach(content => {
                        if (content.id === targetTab) {
                            content.classList.add('active');
                        } else {
                            content.classList.remove('active');
                        }
                    });
                });
            });
            
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab) {
                const tabButton = document.querySelector(`.product-tab[data-tab="${activeTab}"]`);
                if (tabButton) {
                    tabButton.click();
                }
            }
        });
    </script>

</body>
</html>
