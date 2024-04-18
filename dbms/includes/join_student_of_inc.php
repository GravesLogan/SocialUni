<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Name"])){
    
    $Name = $_POST["Name"];
    $_SESSION['College'] = $Name;
    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';
    

    studentJoinUni($conn, $_SESSION["UID"], $Name);
    header("location: ../php/universities.php");

}
else {
    header("location: ../php/universities.php");
    exit();
}