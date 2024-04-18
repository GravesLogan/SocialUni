<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';
    
    $UID = $_SESSION["UID"];
    $EventID = $_POST["EventID"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    
    
    createRating($conn, $EventID, $UID, $rating, $comment);
}
else {
    header("location: ../php/reviews.php");
    exit();
}