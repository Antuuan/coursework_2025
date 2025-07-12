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
    <style>
        .btn-primary {
            background-color: rgb(0, 97, 47) !important;
            border-color: rgba(0, 97, 47, 0) !important;
        }

        /* for some reason this doesn't work when it's put in the stylesheet, but does when I put it in the <style> section of the head */
        /* styles the buttons */
        .icon_size{
            height: 60px;
            width: auto;
            padding-left: 10px;
            padding-right: 10px;
        }

        /* makes the buttons disappear when the screen is thinner than 992px */
        @media(max-width: 992px){
            .icon_size{
                pointer-events: none;
                display: none;
                padding: 0;
                /* makes sure they dont stack and take up space in the navbar */
                height: 0px;
                width: 0px;
            }
        }

    </style>

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
                <a href="basket.php" class="btn btn-primary icon_size">
                    <img src="basket_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
                <a href="favourites.php" class="btn btn-primary icon_size">
                    <img src="favourites_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
            <div class="col-6 text-center main_button">
                <a href="index.php" class="btn btn-primary" style="width: auto; height: 74px;">
                    <img src="logo.png" alt="logo" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
            <div class="col text-center">
                <a href=".php" class="btn btn-primary icon_size">
                    <img src="search_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
                <!-- to keep account from dissapearing at samll screen sizes, use custom style instead of the icon_size class -->
                <a href="account.php" class="btn btn-primary" style="width: auto; height: 60px; padding-left: 10px; padding-right: 10px;">
                    <img src="account_icon.png" style="max-width: 100%; max-height: 100%;">
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