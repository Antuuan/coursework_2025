<?php
session_start();
include_once("connection.php");

print_r($_POST);

// check if user is logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$buyer_id = (int)$_SESSION['user_id'];

// validate incoming data
if (!isset($_POST['item_id']) || !is_numeric($_POST['item_id'])) {
    header("Location: index.php?error=invalid_item");
    exit;
}
echo("<br>");
print_r($buyer_id);

$item_id = (int)$_POST['item_id'];

echo("<br>");
print_r($item_id);
echo("<br>");

// grabs info
$stmt = $conn->prepare("
    SELECT seller_id, status, price 
    FROM tbl_items 
    WHERE item_id = :item_id
");
$stmt->bindParam(":item_id",$item_id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($item);

// checks if item is available
if ($item['status'] != 1) {
    header("Location: item.php?id=$item_id&error=item_not_available");
    exit;
}

// binds seller id
$seller_id = (int)$item['seller_id'];

// begins the transaction
$conn->beginTransaction();

try {

    //puts info into tbl_order (0=compelete, 1=active)
    $stmt = $conn->prepare("INSERT INTO tbl_order(status, seller_id, buyer_id, review, stars) 
        VALUES(1, :seller_id, :buyer_id, NULL, 0)");

    $stmt->bindParam(":seller_id",$seller_id);
    $stmt->bindParam(":buyer_id",$buyer_id);
    $stmt->execute();

    // grabs the last inserted auto-incremented id
    $order_id = $conn->lastInsertId();

    // Add item to tbl_order_contents
    $stmt = $conn->prepare("INSERT INTO tbl_order_contents(order_id, item_id, qty) 
        VALUES(:order_id, :item_id, 1)");
    $stmt->bindParam(":order_id",$order_id);
    $stmt->bindParam(":item_id",$item_id);
    $stmt->execute();

    // Everything ok then commit
    $conn->commit();

    // Success redirect
    header("Location:basket.php");
    exit;
}

catch (Exception $e) {
    // Something went wrong → rollback
    $conn->rollBack();

    // redircts back to product page
    header("Location: item.php?id=$item_id&error=add_to_cart_failed");
    exit;
}

?>