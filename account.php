<?php
include_once("connection.php");
include("navbar.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

$stmt = $conn->prepare("
    SELECT 
        tbl_order.order_id,
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
    WHERE tbl_order.buyer_id = :user_id 
        AND tbl_order.status = 0
    GROUP BY tbl_order.order_id, tbl_items.item_id
    ORDER BY tbl_order.order_id DESC
");
$stmt->bindParam(":user_id",$user_id);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($purchases);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account - <?= htmlspecialchars($username) ?></title>
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
                    <p class="text-muted">User ID: <?= $user_id ?></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-3 mb-5">
                <a href="my_listings.php" class="btn btn-primary btn-lg">
                    View & Manage My Listings
                </a>
                <a href="edit_profile.php" class="btn btn-outline-secondary btn-lg">
                    Edit Personal Information
                </a>
            </div>

            <!-- Past Purchases -->
            <h3 class="mb-4">Your Past Purchases</h3>

            <?php if (empty($purchases)): ?>
                <div class="alert alert-info text-center py-5">
                    <h5>No purchases yet</h5>
                    <p>Once you complete a purchase, it will appear here.</p>
                    <a href="index.php" class="btn btn-primary mt-3">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($purchases as $purchase): 
                        $images = $purchase['imageurls'] ? explode(',', $purchase['imageurls']) : [];
                        $first_image = !empty($images) ? trim($images[0]) : 'placeholder.jpg';
                    ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <img src="uploads/<?= htmlspecialchars($first_image) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($purchase['item_name']) ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($purchase['item_name']) ?></h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars(substr($purchase['item_description'], 0, 100)) ?>...
                                    </p>
                                    <p class="card-text">
                                        <strong>Price:</strong> £<?= number_format($purchase['price'], 2) ?>  
                                        × <?= $purchase['qty'] ?>  
                                        = £<?= number_format($purchase['price'] * $purchase['qty'], 2) ?>
                                    </p>
                                    <small class="text-muted">Order ID: <?= $purchase['order_id'] ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>