<?php
    session_start();
    $_SESSION = array();
    session_destroy();

    header("Location: https://3.222.193.155/login_form.php");
    exit;
?>