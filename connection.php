<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";
//variables for connecting to DB

try {
    //connects to DB
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    //set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo"Connected Successfully";
}
//if the try doesnt work
catch(PDOException $e){
    // echo"Connection Failed: " . $e->getMessage();
}
?>
