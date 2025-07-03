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
</head>
<body class="bg-light">
<!-- flex-column to allow the rows to stack vertically -->
<nav class="navbar navbar-expand-lg flex-column nav_colour">
    <!-- w-100 class makes the row and container take up 100% of the screen width -->
    <div class="container-fluid w-100 top_row">
        <div class="row w-100">
            <div class="col text-center">
                DZT
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
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item nav_colour"><button type="button" class="btn text-white">ACCOUNT</button></li>
                            <li class="nav-item nav_colour"><a href="login.php"><button type="button" class="btn text-white">LOGIN</button></a></li>
                            <li class="nav-item nav_colour"><a href="signUp.php"><button type="button" class="btn text-white">SIGNUP</button></a></li>
                            <li class="nav-item nav_colour"><a href="Books.php"><button type="button" class="btn text-white">ADD BOOK</button></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


</body>