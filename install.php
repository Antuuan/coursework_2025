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

//users table
//stores the SQL statement as a variable
$statement=$conn->prepare("
DROP TABLE IF EXISTS tbl_users;
CREATE TABLE tbl_users
(user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
password VARCHAR(100) NOT NULL,
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
echo "<br>users table created";

//order table
//stores the SQL statement as a variable
$statement=$conn->prepare("
DROP TABLE IF EXISTS tbl_order;
CREATE TABLE tbl_order
(order_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
status TINYINT(1) NOT NULL,
seller_id INT(6) NOT NULL,
buyer_id INT(6) NOT NULL,
review TEXT,
stars TINYINT(1) NOT NULL);
");

//executes the SQL statement
$statement->execute();
$statement->closeCursor();
echo "<br>order table created";

//order contents table
//stores the SQL statement as a variable
$statement=$conn->prepare("
DROP TABLE IF EXISTS tbl_order_contents;
CREATE TABLE tbl_order_contents
(order_id INT(10) NOT NULL,
item_id INT(10) NOT NULL,
qty TINYINT(2) NOT NULL);
");

//executes the SQL statement
$statement->execute();
$statement->closeCursor();
echo "<br>order_contents table created";

//items table
//stores the SQL statement as a variable
$statement=$conn->prepare("
DROP TABLE IF EXISTS tbl_items;
CREATE TABLE tbl_items
(item_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
seller_id INT(6) NOT NULL,
status TINYINT(1) NOT NULL,
price FLOAT(10) NOT NULL,
item_name VARCHAR(100) NOT NULL,
item_description TEXT,
pictures TEXT NOT NULL,
start_date VARCHAR(8) NOT NULL,
end_date VARCHAR(8) NOT NULL);
");

//executes the SQL statement
$statement->execute();
$statement->closeCursor();
echo "<br>items table created";

//admins table
//stores the SQL statement as a variable
$statement=$conn->prepare("
DROP TABLE IF EXISTS tbl_admins;
CREATE TABLE tbl_admins
(username VARCHAR(30) NOT NULL,
password VARCHAR(100) NOT NULL);
");

//executes the SQL statement
$statement->execute();
$statement->closeCursor();
echo "<br>admins table created";