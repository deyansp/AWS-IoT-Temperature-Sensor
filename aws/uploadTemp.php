<?php 
include "db.php";
    function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
    // Basic Authentication over HTTPS
    function auth() {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        
        if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
        
            $credentials = getCredentials(sanitizeInput($_SERVER['PHP_AUTH_USER']));
        
            if ($credentials != false) {
                
                if (password_verify($_SERVER['PHP_AUTH_PW'], $credentials["pass"])) {
                    return true;
                } else {
                    header('HTTP/1.1 401 Unauthorized');
                    return false;
                    exit;
                }
            }
        }
        return false;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (auth()) {
            $device = sanitizeInput($_POST['device']);
            $date = sanitizeInput($_POST['date']);
            $temperature = sanitizeInput($_POST['temperature']);
            $unit = sanitizeInput($_POST['unit']);
            $gap = sanitizeInput($_POST['gap']);

            if (!empty($device) && !empty($date) && !empty($temperature) && !empty($unit) && !empty($gap)) {
                uploadToDB($device, $date, $temperature, $unit, $gap);
            }
        }
        else
            exit;   
    }
?>