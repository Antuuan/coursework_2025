<?php
    include_once("connection.php");
    include("navbar.php");

    // checks of the id exists and if it s a number, the second check prevents errors from user entered url
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        echo("Invalid product.");
    }
    
    $item_id = (int)$_GET["id"];
    $item_id = $_GET["id"];

// gets item data from DB
    $stmt = $conn->prepare("
        SELECT tbl_items.*, tbl_users.username
        FROM tbl_items
        JOIN tbl_users ON tbl_items.seller_id = tbl_users.user_id
        WHERE tbl_items.item_id =:item_id
    ");
    $stmt->bindParam(":item_id", $item_id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($item);

// gets images from DB
    $stmt = $conn->prepare("
        SELECT tbl_items_n_pics.item_id,
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls
        FROM tbl_items_n_pics
        INNER JOIN tbl_pics
        ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
        WHERE tbl_items_n_pics.item_id=:item_id
        GROUP BY
        tbl_items_n_pics.item_id
    ");
    $stmt->bindParam(":item_id", $item_id);
	$stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $images = explode(',', $row['imageurls']);
    // print_r($images)
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Page title uses the product name -->
    <title><?= htmlspecialchars($item["item_name"]) ?></title>
</head>
<body>

<!-- displaying product info -->
<h1><?= htmlspecialchars($item["item_name"]) ?></h1>

<p><strong>Price:</strong>£<?= htmlspecialchars($item["price"]) ?></p>

<p><strong>Seller:</strong><?= htmlspecialchars($item["username"]) ?></p>

<!--nl2br() keeps line breaks from textarea input -->
<p><?= nl2br(htmlspecialchars($item["item_description"])) ?></p>

<!-- images -->
<h3>Images</h3>

<?php
    // looping through the images
    foreach ($images as $img){
        echo ("<img src=uploads\\".$img." height='100px'>");
    }
?>

</body>
</html>