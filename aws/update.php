<?php 
include "db.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = getLatestFromDB();
    
    echo json_encode($data);
    exit;
}
?>