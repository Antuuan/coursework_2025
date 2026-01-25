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
        SELECT tbl_items.item_name as ItName, tbl_items.item_description as Itdesc, tbl_items.price as Itprice, tbl_items.item_id as ItID,
        GROUP_CONCAT(tbl_pics.image_name) AS imageurls
        FROM tbl_items_n_pics
        INNER JOIN tbl_items
        ON tbl_items.item_id = tbl_items_n_pics.item_id
        INNER JOIN tbl_pics
        ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
        WHERE tbl_items.status = 1
        GROUP BY 
        tbl_items.item_id, tbl_items.item_name, tbl_items.item_description, tbl_items.price;

    ");
	$stmt->execute();	
?>

<!-- products display -->
<div class="container-fluid">

<?php
// displays all items
$count=0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //print_r($row);
        // spliiting the single string of image names into individual names
        $images=explode(",",$row["imageurls"]);
        // make new row every 5th item
        if ($count%4==0){
            echo("<div class='row p-4'>");
        }
        // displays first image and desc in a column
        echo ("<div class='col text-center'>
        <a href='item.php?id=".$row['ItID']."'>
        <img src=uploads\\".$images[0]." height='200px'><br>");
        // display name and description
        echo($row["ItName"].' '.$row["Itdesc"]."<br></div>");
        
        if ($count%4==3){
            echo("</div>");
        }
        $count=$count+1;
    }
?>

</body>