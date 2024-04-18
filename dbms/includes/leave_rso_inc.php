<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $RSOID = $_POST["RSOID"];

    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';
    
    studentLeavesRso($conn, $_SESSION["UID"], $RSOID);
    header("location: ../php/rso.php");

}
else {
    header("location: ../php/universities.php");
    exit();
}