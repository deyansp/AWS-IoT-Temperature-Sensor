<?php
    session_start();
    include "sanitizeInput.php";
    include "db.php";
    $email = $password = "";
    class UserData {
        public $email = '';
        public $pass = '';
    }; // for sending to m as json
    $data = new UserData();
    $errors = array(); // for returning any errors to the login form

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // trim needed here since the empty function treats a field with only whitespace as not empty
        $_POST["email"] = trim($_POST["email"]);
        $_POST["pass"] = trim($_POST["pass"]);

        if (empty($_POST["email"])) {
            $errors["empty-email"] = "Username field cannot be blank!";
        }
        else if (empty($_POST["pass"])) {
            $errors["empty-pass"] = "Password field cannot be blank!";
        }
        else {
            $email = sanitizeInput($_POST["email"]);
            $password = $_POST["pass"];

            $data -> email = $email;
            $data -> pass = $password;
            $json = json_encode($data);

            $usertxt = authUser($json);
            $userdata = json_decode($usertxt, true);
            
            if ($userdata["id"] != -1) {
                    $_SESSION["id"] = $userdata['id'];
                    header("Location: https://3.222.193.155/index.php");
                    exit;
                }
            else {
                $errors["invalid-login"] = "Incorrect username or password, please try again";
            }
        }

        if (!empty($errors)) {
            $_SESSION["errors"] = $errors;
            header("Location: https://3.222.193.155/login_form.php");
            exit;
        }
    }

