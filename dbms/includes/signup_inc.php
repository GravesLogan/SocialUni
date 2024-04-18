<?php

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $Fname = $_POST["FirstName"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $PasswordRe = $_POST["PasswordRe"];
    $Role = $_POST["Role"];

    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';

    if (emptyInputSignup($Fname, $Email, $Password, $PasswordRe, $Role) !== false) {
        header("location: ../php/signup.php?error=emptyinput");
        exit();
    }

    if (invalidEmail($Email) !== false) {
        header("location: ../php/signup.php?error=invalidemail");
        exit();
    }

    if (passwordMatch($Password, $PasswordRe) !== false) {
        header("location: ../php/signup.php?error=mismatched");
        exit();
    }

    if (duplicate($conn, $Email) !== false) {
        header("location: ../php/signup.php?error=emailtaken");
        exit();
    }

    createUser($conn, $Fname, $Email, $Password, $Role);
    header("location: ../php/signup.php?error=none");
}
else {
    header("location: ../php/signup.php");
    exit();
}