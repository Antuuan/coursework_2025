<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
include_once("connection.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Require item_id in URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: my_listings.php?error=invalid_listing");
    exit;
}

$item_id = (int)$_GET['id'];

// Fetch the listing + verify ownership
$stmt = $conn->prepare("
    SELECT 
        item_name, item_description, price, status, start_date
    FROM tbl_items
    WHERE item_id = :item_id
    AND seller_id = :seller_id
");
$stmt->bindParam(":item_id", $item_id);
$stmt->bindParam(":seller_id", $user_id);
$stmt->execute();
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    header("Location: my_listings.php?error=not_found_or_not_yours");
    exit;
}

// Fetch current images
$stmt = $conn->prepare("
    SELECT GROUP_CONCAT(image_name) AS imageurls
    FROM tbl_items_n_pics
    INNER JOIN tbl_pics
    ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
    WHERE tbl_items_n_pics.item_id = :item_id
");
$stmt->bindParam(":item_id", $item_id);
$stmt->execute();
$images_row = $stmt->fetch(PDO::FETCH_ASSOC);
$current_images = $images_row['imageurls'] ? explode(',', $images_row['imageurls']) : [];

// Handle form submission
$success_message = $error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['item_name'] ?? '');
    $new_desc = trim($_POST['item_description'] ?? '');
    $new_price = floatval($_POST['price'] ?? 0);
    $new_status = (int)($_POST['status'] ?? $listing['status']);

    // Basic validation
    if (empty($new_name) || $new_price <= 0) {
        $error_message = "Item name and price are required. Price must be positive.";
    }
    else {
        $conn->beginTransaction();
        try {
            // Update item details
            $stmt = $conn->prepare("
                UPDATE tbl_items
                SET item_name = :name, 
                    item_description = :desc, 
                    price = :price, 
                    status = :status
                WHERE item_id = :item_id
                AND seller_id = :seller_id
            ");
            $stmt->bindParam(":status",$new_status);
            $stmt->bindParam(":price",$new_price);
            $stmt->bindParam(":name",$new_name);
            $stmt->bindParam(":desc",$new_desc);
            $stmt->bindParam(":item_id",$item_id);
            $stmt->bindParam(":seller_id",$user_id);
            $stmt->execute();

            $conn->commit();
            $success_message = "Listing updated successfully!";          
            header("Location: my_listings.php?updated=1");
            exit;

        }

        catch (Exception $e) {
        $conn->rollBack();
        $error_message = "Update failed: " . $e->getMessage();
        }
    }
}

include("navbar.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Listing - <?= htmlspecialchars($listing['item_name']) ?></title>
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-4">Edit Listing</h1>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <!-- Form – no enctype needed since no files -->
    <form method="post">
        <div class="mb-3">
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" class="form-control" id="item_name" name="item_name" 
                   value="<?= htmlspecialchars($listing['item_name']) ?>" maxlength="100" required>
        </div>

        <div class="mb-3">
            <label for="item_description" class="form-label">Description</label>
            <textarea class="form-control" id="item_description" name="item_description" rows="5" required><?= htmlspecialchars(trim($listing['item_description'])) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (£)</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01" 
                   value="<?= htmlspecialchars($listing['price']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="1" <?= $listing['status'] == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $listing['status'] == 0 ? 'selected' : '' ?>>Sold/Inactive</option>
            </select>
        </div>
        <?php if (!empty($current_images)): ?>
            <div class="mb-3">
                <label class="form-label">Current Images</label>
                <div class="row g-2">
                    <?php foreach ($current_images as $img): ?>
                        <div class="col-4 col-md-3">
                            <img src="uploads/<?= htmlspecialchars(trim($img)) ?>" 
                                 class="img-thumbnail" alt="Current image" style="height: 120px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                </div>
                <small class="form-text text-muted">Images cannot be added or removed in this version.</small>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
        <a href="my_listings.php" class="btn btn-secondary btn-lg">Cancel</a>
    </form>
</div>

</body>
</html>