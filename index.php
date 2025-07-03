<!DOCTYPE html>
<head>
    <title>DZT</title>
    <!-- importing bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="mystyle.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<!-- flex-column to allow the rows to stack vertically -->
<nav class="navbar navbar-expand-lg flex-column">
    <!-- w-100 class makes the row and container take up 100% of the screen width -->
    <div class="container-fluid w-100">
        <div class="row w-100">
            <div class="col text-center">
                DZT
            </div>
        </div>
    </div>
    <div class="container-fluid w-100">
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
                            <li class="nav-item"><button type="button">ACCOUNT</button></li>
                            <li class="nav-item"><a href="login.php"><button type="button">LOGIN</button></a></li>
                            <li class="nav-item"><a href="signUp.php"><button type="button">SIGNUP</button></a></li>
                            <li class="nav-item"><a href="Books.php"><button type="button">ADD BOOK</button></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


</body>