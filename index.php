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
        GROUP BY 
        tbl_items.item_id, tbl_items.item_name, tbl_items.item_description, tbl_items.price;

    ");
	$stmt->execute();	
?>

<div class="container-fluid">

<?php
// displays all items
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $count=0;
        //print_r($row);
        // spliiting the single string of image names into individual names
        $images=explode(",",$row["imageurls"]);
        // make new row every 5th item
        if ($count==0){
            echo("<div class='row'>");
        }
        // displays first image and desc in a column
        echo ("<div class='col-sm3'><img src=uploads\\".$images[0]." height='100px'><br>");
        // display name and description
        echo($row["ItName"].' '.$row["Itdesc"]."<br></div>");
        echo("<br>");
        
        if ($count==0){
            echo("</div>");
        }
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