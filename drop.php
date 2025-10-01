<?php

$servername = 'localhost';
$username = 'root';
$password= '';
//variables for connecting to server, no database name as it's not created yet

//connects to server
$conn = new PDO("mysql:host=$servername", $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//selects the databese and message to notify database creation was successful
$sql = "USE shop";
$conn->exec($sql);


$statement=$conn->prepare("DROP TABLE IF EXISTS tbl_items_n_pcis;");
$statement->execute();
$statement->closeCursor();

?>