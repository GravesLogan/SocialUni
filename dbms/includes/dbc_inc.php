<?php

$ServerName = "localhost";
$DBusername = "root";
$DBpassword = "";
$DBname = "testdatabase";

$conn = mysqli_connect($ServerName, $DBusername, $DBpassword, $DBname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}