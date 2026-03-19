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
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls,
        tbl_order.status
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

//print_r($cart_items);

// Calculate total
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Cart</title>
    <!-- Bootstrap already in navbar.php, but you can add if needed -->
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-4">Your Cart</h1>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info text-center py-5">
            <h4>Your cart is empty</h4>
            <p>Browse some items and add them to your cart!</p>
            <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Cart items -->
            <div class="col-lg-8">
                <?php foreach ($cart_items as $item): 
                    $images = $item['imageurls'] ? explode(',', $item['imageurls']) : [];
                    $first_image = !empty($images) ? trim($images[0]) : 'placeholder.jpg';
                ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <img src="uploads/<?= htmlspecialchars($first_image) ?>" 
                                     class="img-fluid rounded-start" 
                                     alt="<?= htmlspecialchars($item['item_name']) ?>"
                                     style="height: 175px; object-fit: cover; width: 100%;">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($item['item_name']) ?></h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars(substr($item['item_description'], 0, 100)) ?>...
                                    </p>
                                    <p class="card-text">
                                        <strong>Price:</strong> £<?= number_format($item['price'], 2) ?>  
                                        × <?= $item['qty'] ?>  
                                        = £<?= number_format($item['price'] * $item['qty'], 2) ?>
                                    </p>   
                                    <!-- REMOVE BUTTON -->
                                    <form action="remove_from_cart.php" method="post" class="mt-2">
                                        <input type="hidden" name="order_id" value="<?= $item['order_id'] ?>">
                                        <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm position-sticky" style="top: 20px;">
                    <div class="card-body">
                        <h4 class="card-title">Order Summary</h4>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<?= count($cart_items) ?> items):</span>
                            <span>£<?= number_format($grand_total, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Delivery:</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-4 fw-bold">
                            <span>Total:</span>
                            <span>£<?= number_format($grand_total, 2) ?></span>
                        </div>

                        <a href="checkout.php" class="btn btn-success btn-lg w-100 mt-4">
                            Proceed to Purchase
                        </a>
                        <small class="d-block text-center mt-3 text-muted">
                            This is a simulated checkout – no payment required
                        </small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>