<?php

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';

    if (emptyInputLogin($Email, $Password) !== false) {
        header("location: ../php/login.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $Email, $Password);

} else {
    header("location: ../php/login.php");
    exit();
}