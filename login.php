<DOCTYPE html>
<html>
<body>

<form action="index.php" method="post">
    Username:<input type="text" name="username"><br>
    Password:<input type="text" name="password" minlength="8"><br>
    <button type="submit">submit</button>
</form>

<?php

    session_start();
    include_once("connection.php")