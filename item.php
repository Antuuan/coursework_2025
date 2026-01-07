<?php
    include_once("connection.php");

    // checks of the id exists and if it s a number, the second check prevents errors from user entered url
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        echo("Invalid product.");
    }
    
    $item_id = (int)$_GET["id"];
    $item_id = $_GET["id"];

    $stmt = $conn->prepare("
    SELECT tbl_items.*, tbl_users.username
    FROM tbl_items
    JOIN tbl_users ON tbl_items.seller_id = tbl_users.user_id
    WHERE tbl_items.item_id = :item_id
    ");
    $stmt->bindParam(":item_id", $item_id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($item)
?>