<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';

    $rso_name = $_POST["rso_name"];
    $OwnerID = $_SESSION["UID"];

    if ($OwnerID <= 0) {
        $OwnerID = $_SESSION["UID"];
    }
    

    deleteRSO($conn, $rso_name, $OwnerID);
    header("location: ../php/rso.php?error=none");
}
else {
    header("location: ../php/signup.php");
    exit();
}