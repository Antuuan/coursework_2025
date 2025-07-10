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
                <a href="basket.php" class="btn btn-primary" style="width: 5vw; height: auto;">
                    <img src="basket_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
                <a href="favourites.php" class="btn btn-primary" style="width: 5vw; height: auto;">
                    <img src="favourites_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
            <div class="col-6 text-center main_button">
                <a href="index.php" class="btn btn-primary" style="width: auto; height: 74px;">
                    <img src="logo.png" alt="logo" style="max-width: 100%; max-height: 100%;">
                </a>
            </div>
            <div class="col text-center">
                <a href=".php" class="btn btn-primary" style="width: 5vw; height: auto;">
                    <img src="search_icon.png" style="max-width: 100%; max-height: 100%;">
                </a>
                <a href="account.php" class="btn btn-primary" style="width: 5vw; height: auto;">
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
                            <li class="nav-item outline_desktop padding_desktop"><a href="signUp.php" class="btn text-white">SIGNUP</a></li>
                            <li class="nav-item outline_desktop padding_desktop"><a href="Books.php" class="btn text-white">ADD BOOK</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


</body>