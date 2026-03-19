<?php
$stmt = $conn->prepare("
    SELECT 
        tbl_order.order_id,
        tbl_items.item_id,
        tbl_items.item_name,
        tbl_items.price,
        tbl_items.item_description,
        tbl_order_contents.qty
    FROM tbl_order
    INNER JOIN tbl_order_contents
    ON tbl_order_contents.order_id = tbl_order.order_id
    INNER JOIN tbl_items
    ON tbl_items.item_id = tbl_order_contents.item_id
    WHERE tbl_order.buyer_id = :buyer_id
        AND tbl_order.status = 1
");