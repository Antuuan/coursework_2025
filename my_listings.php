<?php
include_once("connection.php");
include("navbar.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        tbl_items.item_id,
        tbl_items.item_name,
        tbl_items.price,
        tbl_items.item_description,
        tbl_items.status,
        tbl_items.start_date,
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls
    FROM tbl_items
    LEFT JOIN tbl_items_n_pics
    ON tbl_items_n_pics.item_id = tbl_items.item_id
    LEFT JOIN tbl_pics
    ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
    WHERE tbl_items.seller_id = :seller_id
    GROUP BY tbl_items.item_id
    ORDER BY
        CASE WHEN tbl_items.status = 1 THEN 0 ELSE 1 END,
        tbl_items.start_date DESC
");
$stmt->bindParam(":seller_id", $user_id);
$stmt->execute();
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($listings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Listings</title>
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-5 text-center">My Listings</h1>

    <div class="text-center mb-4">
        <a href="products.php" class="btn btn-primary btn-lg">Create New Listing</a>
    </div>

    <?php if (empty($listings)): ?>
        <div class="alert alert-info text-center py-5">
            <h4>You have no listings yet</h4>
            <p>Create your first listing to start selling!</p>
            <a href="products.php" class="btn btn-primary mt-3">Create Listing</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($listings as $listing): 
                $images = $listing['imageurls'] ? explode(',', $listing['imageurls']) : [];
                $first_image = !empty($images) ? trim($images[0]) : 'placeholder.jpg';
                $status_text = ($listing['status'] == 1) ? 'Active' : 'Sold/Completed';
                $status_class = ($listing['status'] == 1) ? 'bg-success' : 'bg-secondary';
            ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="uploads/<?= htmlspecialchars($first_image) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($listing['item_name']) ?>"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($listing['item_name']) ?></h5>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars(substr($listing['item_description'], 0, 100)) ?>...
                            </p>
                            <p class="card-text">
                                <strong>Price:</strong> £<?= number_format($listing['price'], 2) ?><br>
                                <strong>Listed on:</strong> <?= $listing['start_date'] ?><br>
                                <span class="badge <?= $status_class ?>"><?= $status_text ?></span>
                            </p>
                            <?php if ($listing['status'] == 1): ?>  <!-- Active: show Edit -->
                                <a href="edit_listing.php?id=<?= $listing['item_id'] ?>" class="btn btn-primary w-100">
                                    Edit Listing
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>