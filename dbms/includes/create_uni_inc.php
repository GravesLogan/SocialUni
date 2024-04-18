<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';

    $name = $_POST["uni_name"];
    $address = $_POST["uni_adderss"];
    $desc = $_POST["uni_desc"];
    $numofstudents = $_POST["uni_students"];
    
    // Handle uploaded file
    $file = $_FILES["uni_pics"];
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileError = $file["error"];

    if ($fileError === 0) {
        $fileDestination = '../uploads/' . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);
    } else {
        // Handle file upload error
        header("location: ../php/universities.php?error=fileuploaderror&errorCode=$fileError");
        exit();
    }

    if (emptyUni($name, $address, $desc, $numofstudents) !== false) {
        header("location: ../php/universities.php?error=emptyinput");
        exit();
    }
    
    createUni($conn, $name, $address, $desc, $numofstudents, $fileDestination);

}
else {
    header("location: ../php/universities.php");
    exit();
}