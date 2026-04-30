<?php
include("navbar.php");              // Navigation bar with search toggle
include_once("connection.php");     // PDO database connection

// 2. Get and clean the search keyword from URL (GET)
// Using GET so search term appears in URL (bookmarkable, shareable, no resubmit warnings)
$search_query = trim($_GET['q'] ?? '');  // Get 'q' parameter or empty string
$search_active = !empty($search_query);  // Flag: true if user entered a search term

// 3. Build the SQL query safely with prepared statements
// Base query – selects item details + all images grouped together
$sql = "
    SELECT 
        tbl_items.item_id AS ItID,
        tbl_items.item_name AS ItName,
        tbl_items.item_description AS Itdesc,
        tbl_items.price AS Itprice,
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls
    FROM tbl_items_n_pics
    INNER JOIN tbl_items ON tbl_items.item_id = tbl_items_n_pics.item_id
    INNER JOIN tbl_pics ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
";

// Array for any WHERE parameters (prevents SQL injection)
$params = [];

// Array to build WHERE conditions
$where_clauses = [];

// Add search filter if user entered a keyword
if ($search_active) {
    // Search in name OR description (case-insensitive via %wildcards%)
    $where_clauses[] = "(tbl_items.item_name LIKE :search OR tbl_items.item_description LIKE :search)";
    $search_param = "%$search_query%";           // % matches anything before/after term
    $params[':search'] = $search_param;
}

// Only show active/available items (optional but recommended)
$where_clauses[] = "tbl_items.status = 1";

// Combine all conditions with AND
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

// Group by item so each product appears once (with all its images)
$sql .= " GROUP BY tbl_items.item_id";

// Order by newest first (or change to price, name, etc.)
$sql .= " ORDER BY tbl_items.item_id DESC";

// 4. Execute the prepared query safely
$stmt = $conn->prepare($sql);
$stmt->execute($params);               // $params binds :search if used
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Get all matching rows as array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $search_active ? 'Search Results' : 'All Products' ?></title>
</head>
<body class="bg-light">

<div class="container my-5">
    <!-- Page heading – shows search term or "All Products" -->
    <h1 class="mb-4">
        <?php if ($search_active): ?>
            Search Results for "<?= htmlspecialchars($search_query) ?>"
            (<?= count($items) ?> found)
        <?php else: ?>
            All Products
        <?php endif; ?>
    </h1>

    <!-- No results message (only shown when searching) -->
    <?php if (empty($items) && $search_active): ?>
        <div class="alert alert-info text-center py-5">
            <h4>No results found</h4>
            <p>Try different keywords or browse all products.</p >
            Clear Search
        </div>

    <!-- Display items in responsive Bootstrap grid -->
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php foreach ($items as $item): 
                // Split comma-separated image names into array
                $images = $item['imageurls'] ? explode(',', $item['imageurls']) : [];
                // Use first image as thumbnail (or fallback)
                $first_image = !empty($images) ? trim($images[0]) : 'placeholder.jpg';
            ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- Thumbnail image -->
                        <img src="uploads/<?= htmlspecialchars($first_image) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($item['ItName']) ?>"
                             style="height: 200px; object-fit: cover;">

                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($item['ItName']) ?></h5>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars(substr($item['Itdesc'], 0, 60)) ?>...
                            </p >
                            <p class="card-text fw-bold">£<?= number_format($item['Itprice'], 2) ?></p>
                            <!-- Link to full product detail -->
                            <a href="item.php?id=<?= $item['ItID'] ?>" class="btn btn-primary btn-sm">
                                View Item
                            </a >
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>