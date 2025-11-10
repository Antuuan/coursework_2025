<!DOCTYPE html>
<head>
    <title>DZT</title>
    <!-- importing bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="stylesheet.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <!-- bootstrap overrides, idk y it doesnt work in the css sheet but it works here -->

    <?php
        // starts the session to allow use of $_SESSION global
        session_start();

        // isset checks if variable exist and whether it is null or not, === to check if values are equal and the same type
        if(isset($_SESSION["logged_in"])&&$_SESSION["logged_in"]===true){
            $account="account.php";
            $basket="basket.php";
            $fav="favs.php";
        }
        else{
            $account="login.php";
            $basket="login.php";
            $fav="favs.php";
        }

    ?>

</head>

<body class="bg-light">
<!-- flex-column to allow the rows to stack vertically -->
<nav class="navbar navbar-expand-lg flex-column nav_colour">
    <!-- w-100 class makes the row and container take up 100% of the screen width -->
    <div class="container-fluid w-100 top_row">
        <div class="row w-100">
            <!-- by using the bootstrap 5 grid system, the top row is split into 3 columns, one bigger one in the center and smaller ones on the sides -->
            <div class="col text-center">
                <!-- resizes button and makes the image fill the entire button instead of resizing the image -->
                <a href="<?php echo($basket);?>" class="btn btn-primary icon_size">
                    <img src="icons\basket_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
                <a href="<?php echo($fav);?>" class="btn btn-primary icon_size">
                    <img src="icons\favourites_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
            <div class="col-6 text-center main_button">
                <a href="index.php" class="btn btn-primary" style="width: auto; height: 74px;">
                    <img src="icons\logo.png" alt="logo" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
            <div class="col text-center">
                <!-- uses the bootstrap data-bs target to tell the code to look at the search bar, and data-bs-toggle="collapse" tells will toggle the search bar's collapse value to true or false -->
                <a href=".php" class="btn btn-primary icon_size" type="button" data-bs-toggle="collapse" data-bs-target="#search_bar">
                    <img src="icons\search_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
                <!-- to keep account from dissapearing at samll screen sizes, use custom style instead of the icon_size class -->
                <a href="<?php echo($account);?>" class="btn btn-primary" style="width: auto; height: 60px; padding-left: 10px; padding-right: 10px;">
                    <img src="icons\account_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid w-100 nav_colour">
        <div class="row w-100">
            <div class="col w-100">
                <!-- using text-start to make the button on the left -->
                <div class="container-fluid text-start">
                    <!-- hamburger button, navbar_nav here links to the navbar_nav in the collapse menu -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_nav"
                        aria-controls="navbar_nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- collapse and navbar-collapse means this div is collapseable -->
                    <!-- using text-center to make the buttons centered once the menu opens -->
                    <div class="collapse navbar-collapse text-center" id="navbar_nav">
                        <ul class="navbar-nav mx-auto nav_colour">
                            <li class="nav-item outline_desktop padding_desktop"><a href=".php" class="btn text-white">ACCOUNT</a></li>
                            <li class="nav-item outline_desktop padding_desktop"><a href="login.php" class="btn text-white">LOGIN</a></li>
                            <li class="nav-item outline_desktop padding_desktop"><a href="sign_up.php" class="btn text-white">SIGNUP</a></li>
                            <li class="nav-item outline_desktop padding_desktop"><a href="books.php" class="btn text-white">ADD BOOK</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- a search bar at the bottom that will appear when search button is clikced -->
    <div id="search_bar" class="collapse bg-light p-3 border-bottom">
        <div class="container">
            <form action="search.php" method="post" class="d-flex">
                <input class="form-control" type="search" name="q" placeholder="Search...">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<!-- this div has the id of navbar_nav which means the dropdown menu also takes from this div, but its not part of the <nav> -->
<div class="collapse navbar-collapse text-center nav_colour other_buttons" id="navbar_nav">
    <!-- list-unstyled is part of bootstrap 5 that removes the bullet points from <ul> -->
    <ul class="list-unstyled">
        <li class="nav-item outline_desktop padding_desktop"><a href=".php" class="btn text-white">BUTTON1</a></li>
        <li class="nav-item outline_desktop padding_desktop"><a href=".php" class="btn text-white">BUTTON2</a></li>
        <li class="nav-item outline_desktop padding_desktop"><a href=".php" class="btn text-white">BUTTON3</a></li>
        <li class="nav-item outline_desktop padding_desktop"><a href=".php" class="btn text-white">BUTTON4</a></li>
    </ul>
</div>



</body>