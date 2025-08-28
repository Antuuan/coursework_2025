<!DOCTYPE html>
<html>
<head>
    
    <title>Page title</title>
    
</head>
<body>
<!-- form that sends all the information to add_users.php where it will be added to the DB -->
<form action="add_users.php" method="post">
    username:<input type="text" name="username"><br>
    E-mail Address:<input type="text" name="email"><br>
    Password:<input type="text" name="password" minlength="8"><br>
    Phone Number:<input type="text" name="phone_no" minlength="11" maxlength="11"><br>
    Address:<input type="text" name="address"><br>
    Postcode:<input type="text" name="postcode"><br>
    Card number:<input type="text" name="card_no"><br>
    Name on Card:<input type="text" name="card_name"><br>
    Expiry:<input type="text" name="card_expiry"><br>
    CVC:<input type="text" name="cvc"><br>

<?php
	include_once('connection.php');
	$stmt = $conn->prepare("SELECT * FROM tbl_users");
	$stmt->execute();
?>   
</body>
</html>