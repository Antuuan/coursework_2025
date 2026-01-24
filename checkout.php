<?php
include_once("connection.php");
include("navbar.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$buyer_id = (int)$_SESSION['user_id'];

// Check if form was submitted (Confirm Purchase)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_purchase'])) {

    $conn->beginTransaction();
    try {
        // Update all active orders (status = 1) to completed (status = 0)
        $stmt = $conn->prepare("
            UPDATE tbl_order 
            SET status = 0 
            WHERE buyer_id = :buyer_id 
              AND status = 1
        ");
        $stmt->bindParam(":buyer_id", $buyer_id);
        $stmt->execute();

        $conn->commit();

        $purchase_confirmed = true;

    } catch (Exception $e) {
        $conn->rollBack();
        $purchase_error = true;
    }
}

// Fetch current active cart items (only if not just confirmed)
if (!isset($purchase_confirmed)) {
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
    $stmt->bindParam(":buyer_id", $buyer_id);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total
    $grand_total = 0;
    foreach ($cart_items as $item) {
        $grand_total += $item['price'] * $item['qty'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-5 text-center">Checkout</h1>

    <?php if (isset($purchase_confirmed)): ?>
        <div class="alert alert-success text-center py-5" role="alert">
            <h2>Thank you for your purchase!</h2>
            <p>Your order has been successfully completed.</p>
            <p>You can view your past purchases in your <a href="account.php">account</a>.</p>
            <a href="index.php" class="btn btn-primary btn-lg mt-4">Continue Shopping</a>
        </div>
    <?php elseif (isset($purchase_error)): ?>
        <div class="alert alert-danger text-center">
            <h4>Purchase failed</h4>
            <p>Something went wrong. Please try again.</p>
            <a href="basket.php" class="btn btn-secondary">Back to Cart</a>
        </div>
    <?php else: ?>
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center py-5">
                <h4>Your cart is empty</h4>
                <p>You can't checkout with no items.</p>
                <a href="index.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="mb-4">Order Summary</h3>
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
                                         style="height: 140px; object-fit: cover;">
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($item['item_name']) ?></h5>
                                        <p class="card-text text-muted">
                                            <?= htmlspecialchars(substr($item['item_description'] ?? '', 0, 120)) ?>...
                                        </p>
                                        <p class="card-text fw-bold">
                                            £<?= number_format($item['price'], 2) ?>  
                                            × <?= $item['qty'] ?>  
                                            = £<?= number_format($item['price'] * $item['qty'], 2) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm position-sticky" style="top: 20px;">
                        <div class="card-body">
                            <h4 class="card-title">Payment Summary</h4>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items (<?= count($cart_items) ?>):</span>
                                <span>£<?= number_format($grand_total, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Delivery:</span>
                                <span>Free</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-4 fw-bold mb-4">
                                <span>Total:</span>
                                <span>£<?= number_format($grand_total, 2) ?></span>
                            </div>

                            <form method="post" action="checkout.php">
                                <button type="submit" name="confirm_purchase" value="1" class="btn btn-success btn-lg w-100">
                                    Confirm Purchase
                                </button>
                            </form>

                            <small class="d-block text-center mt-3 text-muted">
                                This is a simulated purchase – no real payment taken.
                            </small>
                            <div class="mt-3 text-center">
                                <a href="basket.php" class="text-decoration-none">← Back to Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>