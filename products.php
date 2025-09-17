<!DOCTYPE html>
<html>
<head>
    
    <title>Page title</title>
    <?php
        session_start();
        include_once("connection.php");

        if(isset($_SESSION["logged_in"])&&$_SESSION["logged_in"]===true){
            $user=$_SESSION["username"];
        }
        else{
            header("Location:login.php");
        }
    ?>
    
</head>
<body>

<!-- form that sends all the information to add_users.php where it will be added to the DB -->
<!-- need enctype to make files work -->
<form action="add_products.php" method="post" enctype="multipart/form-data">
    Item Name:<input type="text" name="item_name" maxlength="100"><br>
    Item Description:<input type="text" name="item_description"><br>
    Images:<input type="file" id="pics" name="pics" accept="image/*"><br>
    Price:<input type="text" name="price"><br>
    <button type="submit">submit</button>
</form>

<?php
	include_once('connection.php');
	$stmt = $conn->prepare("SELECT * FROM tbl_items");
	$stmt->execute();
?>   
</body>
</html>