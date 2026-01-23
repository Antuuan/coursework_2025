<DOCTYPE html>
<html>
<body>

<form action="login.php" method="post">
    Username:<input type="text" name="username"><br>
    Password:<input type="text" name="password"><br>
    <input type="submit" value="login">
</form>

<?php
// connects to the DB
    session_start();
    include_once("connection.php");
    
// first checks if the data is sent via post, basically stops the page from breaking when no data is entered
    if ($_SERVER["REQUEST_METHOD"]=="POST"){

        // binds the posted data to variables
        $username=$_POST["username"];
        $password=$_POST["password"];

        // prepares the SQL statement used to find locate the username
        $stmt=$conn->prepare("SELECT * FROM tbl_users WHERE username=:username LIMIT 1");
        $stmt->bindParam(":username",$username);
        $stmt->execute();

        // fetch the user row if a match is found
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
        
        // compares the password entered and the hashed password stored in the DB by using password_verify
        if ($user && password_verify($password, $user["password"])){
            
            // uses the session superglobal to store data about if the user is logged in, which can be accessed on other pages
            $_SESSION["logged_in"]=true;
            $_SESSION["username"]=$user["username"];
            $_SESSION["user_id"]=$user["user_id"];
            $_SESSION["role"]="0";

            print_r($_SESSION);

            header("Location: index.php");
            exit;
        }
        else{
            echo("invalid details");
        }
    }