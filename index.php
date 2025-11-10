<!DOCTYPE html>
<head>

</head>

<body>

<!--getes the navbar onto page -->
<?php
include("navbar.php")
?>

<?php
    // joins all 3 tables to allow you to access individual things from each table
	include_once('connection.php');
	$stmt = $conn->prepare("
        SELECT tbl_items.item_name as ItName, tbl_items.item_description as Itdesc, tbl_items.price as Itprice,
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls
        FROM tbl_items_n_pics
        INNER JOIN tbl_items
        ON tbl_items.item_id = tbl_items_n_pics.item_id
        INNER JOIN tbl_pics
        ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
    ");
	$stmt->execute();	
    
// displays all items
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // spliiting the single string of image names into individual names
        $images=explode(",",$row["imageurls"]);
        echo ("<img src=uploads\\".$images[0]." height='100px'>");
        // display name and description
        echo($row["ItName"].' '.$row["Itdesc"]."<br>");
        echo("<br>");
    }
?>











<?php 
// might be useful later!!
    // looping through the images
    // foreach ($images as $img){
    //     echo ("<img src=uploads\\".$img." height='100px'>");
    // }
?>

</body>