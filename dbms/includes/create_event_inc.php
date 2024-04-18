<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $event_name = $_POST["event_name"];
    $event_desc = $_POST["event_desc"];
    $event_date = $_POST["event_date"];
    $event_addr = $_POST["event_addr"];
    $event_Stime = $_POST["event_start"];
    $event_Etime = $_POST["event_end"];
    $event_num = $_POST["event_number"];
    $event_email = $_POST["event_email"];
    $event_restriction = $_POST["restriction"];

    require_once 'dbc_inc.php';
    require_once 'functions_inc.php';

    if (emptyEvent($event_name, $event_desc, $event_date, $event_addr, $event_Stime, $event_Etime, $event_num, $event_email, $event_restriction) !== false) {
        header("location: ../php/events.php?error=emptyinput");
        exit();
    }
    

    if(isset($_POST["RSOID"])) {
        createEvent($conn, $event_name, $event_desc, $event_date, $event_addr, $event_Stime, $event_Etime, $event_num, $event_email, $event_restriction, $_POST["RSOID"]);
        header("location: ../php/events.php?error=MadeItToTheRSOID");
    } else {
        createEvent($conn, $event_name, $event_desc, $event_date, $event_addr, $event_Stime, $event_Etime, $event_num, $event_email, $event_restriction, '');
    }
}
else {
    header("location: ../php/events.php");
    exit();
}