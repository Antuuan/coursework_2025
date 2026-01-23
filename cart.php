<?php
session_start();
include_once("connection.php");
include("navbar.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$buyer_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        tbl_order.order_id,
        tbl_items.item_id,
        tbl_items.item_name,
        tbl_items.price,
        tbl_items.item_description,
        tbl_order_contents.qty,
        GROUP_CONCAT(p.image_name) AS imageurls
    FROM tbl_order
    INNER JOIN tbl_order_contents
    ON tbl_order_contents.order_id = tbl_order.order_id
    INNER JOIN tbl_items
    ON tbl_items.item_id = tbl_order_contents.item_id
    LEFT JOIN tbl_items_n_pics ip
    ON ip.item_id = tbl_items.item_id
    LEFT JOIN tbl_pics p
    ON p.pic_id = ip.pic_id
    WHERE tbl_order.buyer_id = :buyer_id 
        AND tbl_order.status = 1
    GROUP BY tbl_order.order_id, tbl_items.item_id, tbl_items.item_name, tbl_items.price, tbl_items.item_description, tbl_order_contents.qty
    ORDER BY tbl_order.order_id DESC
");
?>