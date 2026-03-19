<?php
session_start();
include_once("connection.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
    !isset($_POST['order_id']) || !is_numeric($_POST['order_id']) ||
    !isset($_POST['item_id']) || !is_numeric($_POST['item_id'])) {
    header("Location: basket.php");
    exit;
}

$order_id = (int)$_POST['order_id'];
$item_id  = (int)$_POST['item_id'];
$buyer_id = (int)$_SESSION['user_id'];

$conn->beginTransaction();

try {
    // Delete the specific line item
    $stmt = $conn->prepare("
        DELETE tbl_order_contents FROM tbl_order_contents
        INNER JOIN tbl_order  ON tbl_order.order_id = tbl_order_contents.order_id
        WHERE tbl_order_contents.order_id = :order_id 
          AND tbl_order_contents.item_id = :item_id 
          AND tbl_order.buyer_id = :buyer_id
          AND tbl_order.status = 1
    ");

    $stmt->bindParam(":buyer_id",$buyer_id);
    $stmt->bindParam(":item_id",$item_id);
    $stmt->bindParam(":order_id",$order_id);
    $stmt->execute();

    // Check if this order now has zero items → delete the order header too
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM tbl_order_contents 
        WHERE order_id = :order_id
    ");

    $stmt->bindParam(":order_id",$order_id);
    $stmt->execute();
    $remaining = $stmt->fetchColumn();

    // print_r($remaining);
    
    if ($remaining == 0) {
        $stmt = $conn->prepare("
            DELETE FROM tbl_order 
            WHERE order_id = :order_id 
              AND buyer_id = :buyer_id 
              AND status = 1
        ");
        $stmt->bindParam(":order_id",$order_id);
        $stmt->bindParam(":buyer_id",$buyer_id);
        $stmt->execute();
    }

    $conn->commit();

    // Redirect back to cart
    //header("Location: cart.php?removed=1");
    exit;

}
catch (Exception $e) {
    $conn->rollBack();
    //header("Location: cart.php?error=remove_failed");
    exit;
}
