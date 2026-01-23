<?php
include_once("connection.php");
include("navbar.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$buyer_id = (int)$_SESSION['user_id'];

// grabs all needed info
$stmt = $conn->prepare("
    SELECT 
        tbl_order.order_id,
        tbl_items.item_id,
        tbl_items.item_name,
        tbl_items.price,
        tbl_items.item_description,
        tbl_order_contents.qty,
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls
    FROM tbl_order
    INNER JOIN tbl_order_contents
    ON tbl_order_contents.order_id = tbl_order.order_id
    INNER JOIN tbl_items
    ON tbl_items.item_id = tbl_order_contents.item_id
    LEFT JOIN tbl_items_n_pics
    ON tbl_items_n_pics.item_id = tbl_items.item_id
    LEFT JOIN tbl_pics
    ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
    WHERE tbl_order.buyer_id = :buyer_id 
        AND tbl_order.status = 1
    GROUP BY tbl_order.order_id, tbl_items.item_id, tbl_items.item_name, tbl_items.price, tbl_items.item_description, tbl_order_contents.qty
    ORDER BY tbl_order.order_id DESC
");

$stmt->bindParam(":buyer_id",$buyer_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($cart_items);

// Calculate total
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += $item['price'] * $item['qty'];
}
?>