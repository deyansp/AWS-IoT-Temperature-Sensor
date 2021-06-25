<?php
include "navbar.php";
$error = array();
if (isset($_SESSION["id"])) {
    header("Location: https://3.222.193.155/index.php");
    exit;
}
if (isset($_SESSION["errors"])) {
    $error = $_SESSION["errors"];
}

?>
<link rel="stylesheet" type="text/css" href="form.css">

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Log In</h5>
                    <form class="form-signin" role="form" method="post" action="login.php">
                    <?php if (isset($error["invalid-login"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem;">'.$error["invalid-login"].'</div>';?>
                        <div class="form-label-group">
                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter username" required autofocus>
                            <label for="email">Username</label>
                            <?php if (isset($error["empty-email"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["empty-email"].'</div>';?>
                        </div>
                        <div class="form-label-group">
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter password"
                                   required>
                            <label for="pass">Password</label>
                            <?php if (isset($error["empty-pass"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["empty-pass"].'</div>';?>
                        </div>
                        <button id="logInBtn" type="submit" style="margin-top: 1rem;" class="btn btn-lg btn-primary btn-block text-uppercase">Log
                            In
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION["errors"]); ?>