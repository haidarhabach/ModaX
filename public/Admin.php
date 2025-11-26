<?php
include 'db.php';
//from add admin form m 3melneha ba3ed ha k ui 
$name    = $_POST['name'];
$category= $_POST['category'];
$color   = $_POST['color'];
$type    = $_POST['type'];
$quantity= $_POST['quantity'];

$stmt = $connect->prepare("
    INSERT INTO products (name, category, color, type, quantity)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssi", $name, $category, $color, $type, $quantity);
$stmt->execute();

// eza lproduct lzedne sar fi meno kameye 
if ($quantity > 0) {
    include "notify_available.php";
}
echo "Product add sir";
?>
