<?php
include 'db.php';

$product_id = $_POST['product_id'];
$email      = $_POST['email'];
$category   = $_POST['category'];
$color      = $_POST['color'];
$type       = $_POST['type'];
$stmt = $connect->prepare("
    INSERT INTO stock_notifications (product_id, email, category, color, type)
    VALUES (?, ?, ?, ?, ?,0,now())
");

$stmt->bind_param("issss", $product_id, $email, $category, $color, $type);
$stmt->execute();

echo "Your notification has been sent <br>
<a href=index.php > click to back to shop :)</a>";
?>
