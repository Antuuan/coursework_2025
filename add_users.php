<?php
// echo($_POST);
// header("Location:signup.php");
include_once("connection.php");
array_map("htmlspecialchars",$_POST);
print_r($_POST);

// puts the values from the users page into the database
$stmt=$conn->prepare("INSERT INTO tbl_users (user_id,username,address,email,password,postcode,phone_no,card_no,card_name,card_expiry,cvc)
VALUES (NULL,:username,:address,:email,:password,:postcode,:phone_no,:card_no,:card_name,:card_expiry,:cvc)");

// uses password_hash function to hash the password for security by using the best availiable algorithm (PASSWORD_DEFALT) and stores it in a variable
$password= password_hash($_POST["password"],PASSWORD_DEFAULT);

$stmt->bindParam(':username', $_POST["username"]);
$stmt->bindParam(':address', $_POST["address"]);
$stmt->bindParam(':postcode', $_POST["postcode"]);
$stmt->bindParam(':email', $_POST["email"]);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':phone_no', $_POST["phone_no"]);
$stmt->bindParam(':card_no', $_POST["card_no"]);
$stmt->bindParam(':card_name', $_POST["card_name"]);
$stmt->bindParam(':card_expiry', $_POST["card_expiry"]);
$stmt->bindParam(':cvc', $_POST["cvc"]);

$stmt->execute();
?>