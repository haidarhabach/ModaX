<?php
include 'db.php';
//demo backend hone l id jey mn lsearch aw lhome page  whtrje3 1 row 
$id = $_GET['id'];
$stmt = $connect->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
?>

<h1><?= $product['name'] ?></h1>
<p>Category: <?= $product['category'] ?></p>
<p>Color: <?= $product['color'] ?></p>
<p>Type: <?= $product['type'] ?></p>

<?php if ($product['quantity'] == 0): ?>
    <h3>out of stock</h3>

    <form action="notify_me.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="category" value="<?= $product['category'] ?>">
        <input type="hidden" name="color" value="<?= $product['color'] ?>">
        <input type="hidden" name="type" value="<?= $product['type'] ?>">

        <label>Enter your email to notify:</label>
        <input type="email" name="email" required >

        <button type="submit">Notify me</button>
    </form>
<?php endif; ?>

