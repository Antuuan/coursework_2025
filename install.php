<?php

$servername = 'localhost';
$username = 'root';
$password= '';
//variables for connecting to server, no database name as it's not created yet

//connects to server
$conn = new PDO("mysql:host=$servername", $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//creates the database using SQL
$sql = "CREATE DATABASE IF NOT EXISTS shop";
$conn->exec($sql);

//selects the databese and message to notify database creation was successful
$sql = "USE shop";
$conn->exec($sql);
echo "DB created successfully";

//stores the SQL statement as a variable
$statement=$conn->prepare("
DROP TABLE IF EXISTS tbl_users;
CREATE TABLE tbl_users
(user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(100) NOT NULL,
username VARCHAR(50) NOT NULL,
phone_no VARCHAR(11) NOT NULL,
address VARCHAR(100) NOT NULL,
postcode VARCHAR(7) NOT NULL,
card_no VARCHAR(300) NOT NULL,
card_name VARCHAR(300) NOT NULL,
card_expiry VARCHAR(5) NOT NULL,
cvc VARCHAR(300) NOT NULL,
");

//executes the SQL statement
$statement->execute();
$statement->closeCursor();
echo "users table created"
