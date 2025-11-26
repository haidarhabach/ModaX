<?php
$stmt = $connect->prepare("
    SELECT * FROM stock_notifications 
    WHERE notified = 0
    AND (
        category = ? 
        OR color = ? 
        OR type = ?
    )
");
$stmt->bind_param("sss", $category, $color, $type);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];

    mail(
        $email,
        "new Product !!",
        "the product now is available ! . check it out ! "
    );
    //nhna by default 3tbrna kel notify b data base =0 w kel m b3tna lwhd bnhta 1 wkel m whd byb3t btsr 0 eza kent 1 
    
    $update = $connect->prepare("UPDATE stock_notifications SET notified=1 WHERE id=?");
    $update->bind_param("i", $row['id']);
    $update->execute();
}
?>
