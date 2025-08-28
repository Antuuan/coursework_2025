<?php
// echo($_POST);
// header("Location:users.php");
include_once("connection.php");
array_map("htmlspecialchars",$_POST);
print_r($_POST);

switch($_POST["role"]){
	case "user":
		$role=0;
		break;
		echo $role;
	case "admin":
		$role=1;
		break;
		echo $role;
};

$stmt = $conn->prepare("INSERT INTO tbl_users (user_id,username,address,email,password,postcode,phone_no,card_no,card_name,card_expiry,cvc,role)
VALUES (NULL,:username,:address,:email,:password,:postcode,:phone_no,:card_no,:card_name,:card_expiry,:cvc,:role)");

$stmt->bindParam(':username', $_POST["username"]);
$stmt->bindParam(':address', $_POST["address"]);
$stmt->bindParam(':postcode', $_POST["postcode"]);
$stmt->bindParam(':email', $_POST["email"]);
$stmt->bindParam(':password', $_POST["password"]);
$stmt->bindParam(':phone_no', $_POST["phone_no"]);
$stmt->bindParam(':card_no', $_POST["card_no"]);
$stmt->bindParam(':card_name', $_POST["card_name"]);
$stmt->bindParam(':card_expiry', $_POST["card_expiry"]);
$stmt->bindParam(':cvc', $_POST["cvc"]);
$stmt->bindParam(':role', $role);

$stmt->execute();
?>