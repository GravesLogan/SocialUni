<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';

    $rso_name = $_POST["rso_name"];
    $OwnerID = $_SESSION["UID"];

    if ($OwnerID <= 0) {
        $OwnerID = $_SESSION["UID"];
    }
    
    

    $RSOID = createRSO($conn, $rso_name, $OwnerID);
    studentJoinRso($conn, $OwnerID, $RSOID);
    header("location: ../php/rso.php?error=none");
}
else {
    header("location: ../php/signup.php");
    exit();
}