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

