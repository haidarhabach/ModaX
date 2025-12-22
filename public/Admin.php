<?php
include '../includes/db.php';
$stmt = $connect->prepare("select id from products");
    $stmt->execute();
    $result=$stmt->get_result();
    $id = mt_rand(1, 10000000);
    while ($row = $result->fetch_assoc()) {
        if ($row["id"] != $id) {
            break;
        } else {
            $id = mt_rand(1, 10000000);
        }
    }
//from add admin form m 3melneha ba3ed ha k ui 
$name    = $_POST['name'];
$category= $_POST['category'];
$description   = $_POST['description'];
$price    = $_POST['price'];
$quantity= $_POST['quantity'];
$priceSale= $_POST['priceSale'];
$stmt = $connect->prepare("
    INSERT INTO products (name, category, color, type, quantity)
    VALUES (?,?, ?,?, ?, ?, ?,now())
");
$stmt->bind_param("iissddi",$id,$category, $name,$description, $price, $priceSale, $quantity);
$stmt->execute();

// eza lproduct lzedne sar fi meno kameye 
if ($quantity > 0) {
    include "notify_available.php";
}
echo "Product add sir";
?>
