<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temperature Meter</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
    
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" alt="home">Temperature Meter</a>

            <button id="button" class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse">
                <span class="navbar-toggler-icon ml-auto"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <?php
                        // if user is logged in display log out link
                        if ( isset($_SESSION['id']) ) {
                            echo '<li class="nav-item">
                                <a class="nav-link" href="logout.php">Log Out</a>
                            </li>';
                        }
                        else {
                            echo '<li class="nav-item">
                                <a class="nav-link" href="login_form.php">Log In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="signup.php">Sign Up</a>
                            </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>