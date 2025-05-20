<?php

$servername = 'localhost';
$username = 'root';
$password= '';
//note no Database mentioned here!!

$conn = new PDO("mysql:host=$servername", $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "CREATE DATABASE IF NOT EXISTS shop";
$conn->exec($sql);
//next 3 lines optional only needed really if you want to go on an do more SQL here!
$sql = "USE shop";
$conn->exec($sql);
echo "DB created successfully";