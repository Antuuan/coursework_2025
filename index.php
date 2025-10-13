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
    tbl_pics.image_name as imname
    FROM tbl_items_n_pics
    INNER JOIN tbl_items
    ON tbl_items.item_id = tbl_items_n_pics.item_id
    INNER JOIN tbl_pics
    ON tbl_pics.pic_id = tbl_items_n_pics.pic_id
    ");
	$stmt->execute();	
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        #print_r($row);
        echo($row["ItName"].' '.$row["Itdesc"]."<br>");
        for (){
            echo ("<img src=uploads\\".$row["imname"].">");
        }
    }

?>   

</body>