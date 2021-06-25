<?php
include "navbar.php";
if (isset($_SESSION["id"])) {
    header("Location: http://localhost/index.php");
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
                    <h5 class="card-title text-center">Registration Form</h5>
                    <form class="form-signin" id="form" role="form" method="post" action="/c/register.php">
                        <?php if (isset($error["empty"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem;">'.$error["empty"].'</div>';?>
                        <div class="form-label-group">
                            <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Enter nickname"
                                   required maxlength="30">
                            <label for="nickname">Nickname (impersonal)</label>
                            <?php if (isset($error["nickname"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["nickname"].'</div>';?>
                            <?php if (isset($error["nicknameLength"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["nicknameLength"].'</div>';?>
                        </div>
                        <div class="form-label-group">
                            <input type="email" class="form-control" name="newEmail" id="email" placeholder="Enter email address"
                                   required>
                            <label for="email">Email</label>
                            <?php if (isset($error["email"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["email"].'</div>';?>
                        </div>
                        <div class="form-label-group">
                            <input type="password" class="form-control" name="newPass" id="pwbox"
                                   placeholder="Enter new password" required minlength="8">
                            <label for="pwbox">Password</label>
                            <?php if (isset($error["notMatching"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["notMatching"].'</div>';?>
                            <?php if (isset($error["passLength"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["passLength"].'</div>';?>
                        </div>
                        <div class="form-label-group">
                            <input type="password" class="form-control" name="confirmPass" id="confirmPass"
                                   placeholder="Retype password to confirm" required minlength="8">
                            <label for="confirmPass">Confirm Password</label>
                            <?php if (isset($error["notMatching"])) echo '<div class="alert alert-danger" role="alert" style="border-radius: 1rem; margin-top:1rem;">'.$error["notMatching"].'</div>';?>
                            <span id="message"></span>
                            <p>Already have an account? <a href="login_form.php">Log in here</a></p>
                        </div>
                        <button id="registerBtn" type="submit" onclick="setEnd()" class="btn btn-lg btn-primary btn-block text-uppercase">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#pwbox, #confirmPass').on('keyup', function () {
        if ($('#pwbox').val() == $('#confirmPass').val()) {
            $('#message').html('Passwords Match').css('color', 'green');
            $('#registerBtn').attr("disabled", false);
        } else {
            $('#message').html("Passwords Don't Match").css('color', 'red');
            $('#registerBtn').attr("disabled", true);
        }
    });
</script>
<?php unset($_SESSION["errors"]);?>