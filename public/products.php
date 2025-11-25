<html>

<head>
    <?php
    $category = $_GET['category'] ?? 'all';
    require '../includes/db.php';
    if ($category === 'all') {
        $stmt = $conn->prepare("SELECT * FROM products");
    } else {
        $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
        $stmt->bind_param("s", $category);
    }

    $stmt->execute();
    $products = $stmt->get_result();



    ?>
</head>

<body>
    //rja3 8ayron eza bdk 3melta 3ashen a3ml test bs tzakaret 2eno ma 3ana data b3d
    <?php while ($row = $products->fetch_assoc()): ?>
        <div class="product-card">
            <h4><?= htmlspecialchars($row['name']) ?></h4>
            <p><?= htmlspecialchars($row['price']) ?>$</p>
            <img src="uploads/<?= $row['image'] ?>" alt="">
        </div>
    <?php endwhile; ?>

</body>

</html>

