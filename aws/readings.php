<?php
include "navbar.php";
if (!isset($_SESSION['id'])) {
    header("Location: https://3.222.193.155/login_form.php");
    exit;
}
include 'db.php';
?>
 <style>
        body {
            background: #007bff;
            background: linear-gradient(to right, #0062E6, #33AEFF);
        }
        .container {
            background: #fff;
            padding: 4rem;
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }
    </style>

<div class="container" style="margin-top: 1rem;">
<h2 class="dispaly-4" style="margin-bottom: 1rem;">Temperature History</h4>
<?php getAllFromDB(); ?>
</div>
