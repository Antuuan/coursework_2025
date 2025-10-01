<?php
session_start();
include_once("connection.php");
// gets rid of special characters
array_map("htmlspecialchars", $_POST);
header("Location:index.php");

// uses the username value stored in SESSION global to get user_id from DB
$user=$_SESSION["username"];
$stmt=$conn->prepare("SELECT user_id FROM tbl_users WHERE username=:seller");
$stmt->bindParam(":seller",$user);
$stmt->execute();
$seller=$stmt->fetch(PDO::FETCH_ASSOC);
print_r($seller);

// insert everything into tbl_items except images
$stmt=$conn->prepare("INSERT INTO tbl_items(item_id,seller_id,status,price,item_name,item_description,start_date,end_date)
VALUES(NULL,:seller_id,:status,:price,:item_name,:item_description,:start_date,NULL)");

$status=1;
$start_date=date("Y-m-d");

$stmt->bindParam(":seller_id",$seller["user_id"]);
$stmt->bindParam(":status",$status);
$stmt->bindParam(":price",$_POST["price"]);
$stmt->bindParam(":item_name",$_POST["item_name"]);
$stmt->bindParam(":item_description",$_POST["item_description"]);
$stmt->bindParam(":start_date",$start_date);
$stmt->execute();

// gets the latest inserted id in DB which would be item_id from above
$item_id=$conn->lastInsertId();

$total = count($_FILES["pics"]["name"]);
for ($i = 0; $i<$total; $i++) {
    // get temporary file path for the file $i
    $tmp_name=$_FILES["pics"]["tmp_name"][$i];

    // get original file name to store in DB
    $original_name=$_FILES["pics"]["name"][$i];

    // move file to permanent location (uploads folder)
    $target_path="uploads/".basename($original_name);
    move_uploaded_file($tmp_name, $target_path);

    // insert into database
    $stmt=$conn->prepare("INSERT INTO tbl_pics (pic_id,image_name) 
    VALUES (NULL,:image_name)");
    $stmt->bindValue(":image_name",$original_name,PDO::PARAM_STR);
    $stmt->execute();

    // gets the latest inserted id in DB which would be pic_id from above, and would keep updating every loop
    $pic_id=$conn->lastInsertId();

    $stmt=$conn->prepare("INSERT INTO tbl_items_n_pics (pic_id,item_id)
    VALUES (:pic_id,:item_id)");
    $stmt->bindValue(":item_id",$item_id,PDO::PARAM_INT);
    $stmt->bindValue(":pic_id",$pic_id,PDO::PARAM_INT);
    $stmt->execute();
}
?>
