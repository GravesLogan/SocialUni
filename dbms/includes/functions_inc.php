<?php

session_start();

function emptyInputSignup($Fname, $Email, $Password, $PasswordRe, $Role) {
    $result = true;
    if (empty($Fname) || empty($Email) || empty($Password) || empty($PasswordRe) || $Role === "---") {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function invalidEmail($Email) {
    $result = true;
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function passwordMatch($Password, $PasswordRe) {
    $result = true;
    if ($Password !== $PasswordRe) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function duplicate($conn, $Email) {
    $sql = "SELECT * FROM users WHERE Email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $Email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $Fname, $Email, $Password, $Role) {
    $sql = "INSERT INTO users (FirstName, Email, Password) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($Password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $Fname, $Email, $hashedPwd);
    mysqli_stmt_execute($stmt);

    $insertedUserId = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    // Insert into Admin
    if ($Role === 'admin') {
        $sql = "INSERT INTO admin (UID) VALUES (?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../php/signup.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $insertedUserId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        header("location: ../php/signup.php?error=none");
    }

    // Insert into Super Admin
    if ($Role === 'super_admin') {
        $sql = "INSERT INTO superadmin (UID) VALUES (?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../php/signup.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $insertedUserId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        header("location: ../php/signup.php?error=none");
    }
}



function emptyInputLogin($Email, $Password) {
    $result = true;
    if (empty($Email) || empty($Password)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}
function emptyEvent($event_name, $event_desc, $event_date, $event_addr, $event_Stime, $event_Etime, $event_num, $event_email, $event_restriction) {
    $result = true;
    if (empty($event_name) || empty($event_desc) || empty($event_Stime) || empty($event_Etime) || empty($event_num) || empty($event_email) || empty($event_addr || empty($event_date) || $event_restriction === "---")) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emptyUni($name, $address, $desc, $numofstudents) {
    $result = true;
    if (empty($name) || empty($address) || empty($desc) || empty($numofstudents)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emptyRating($rating, $comment) {
    $result = true;
    if (empty($rating) || empty($comment)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function createRating($conn, $EventID, $UID, $rating, $comment) {
    $sql = "SELECT COUNT(*) AS count FROM reviews WHERE UID = ? AND EventID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // Handle the SQL error
    } else {
        mysqli_stmt_bind_param($stmt, "ii", $UID, $EventID); // Assuming UID is an integer
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $rowCount = $row['count'];

        // If a row with the UID already exists, perform an UPDATE
        if ($rowCount > 0) {
            $sql = "UPDATE reviews SET Rating = ?, Comments = ? WHERE UID = ? AND EventID = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                // Handle the SQL error
            } else {
                // Bind the parameters and execute the statement for UPDATE
                mysqli_stmt_bind_param($stmt, "ssii", $rating, $comment, $UID, $EventID); // Adjust the parameters accordingly
                mysqli_stmt_execute($stmt);
                // Handle success or error
            }
        } else {
            // If no row with the UID exists, perform an INSERT
            $sql = "INSERT INTO reviews (EventID, UID, Rating, Comments) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                // Handle the SQL error
            } else {
                // Bind the parameters and execute the statement for INSERT
                mysqli_stmt_bind_param($stmt, "iiss", $EventID, $UID, $rating, $comment); // Adjust the parameters accordingly
                mysqli_stmt_execute($stmt);
                // Handle success or error
            }
        }
    }
    header("location: ../php/reviews.php?error=none");
    exit();
}

function loginUser($conn, $Email, $Password) {
    $UIDexists = duplicate($conn, $Email);

    if ($UIDexists === false) {
        header("location: ../php/login.php?error=wronglogin1");
        exit();
    }

    if (password_verify($Password, $UIDexists["Password"]) === false) {
        header("location: ../php/login.php?error=wronglogin2");
        exit();
    } else {
        $_SESSION["UID"] = $UIDexists["UID"];
        $_SESSION["role"] = $UIDexists["role"];
        if (($temp = getValueFromTable($conn, "admin", "UID", $_SESSION["UID"], "ID")) !== NULL) {
            $_SESSION["AdminID"] = $temp;
        }
        header("location: ../php/home.php");
        exit();
    }
}

function getValueFromTable($conn, $tableName, $searchColumn, $searchValue, $valueColumn) {
    // Prepare the SQL statement
    $result = 0;
    $sql = "SELECT $valueColumn FROM $tableName WHERE $searchColumn = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $searchValue);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        return $result;
    } else {
        // Handle the case where the statement preparation fails
        return false;
    }
}

function createRSO($conn, $rso_name, $OwnerID) {
    $sql = "INSERT INTO rsos (Name, Owner) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/rso.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "si", $rso_name, $OwnerID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "SELECT RSOID FROM rsos WHERE Name = ? AND Owner = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $rso_name, $OwnerID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function deleteRSO($conn, $rso_name, $OwnerID) {
    $sql = "DELETE FROM rsos WHERE Name = ? AND Owner = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/rso.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "si", $rso_name, $OwnerID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "SELECT RSOID FROM rsos WHERE Name = ? AND Owner = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $rso_name, $OwnerID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $RSOID = $result;

    $sql = "DELETE FROM rso_members WHERE RSOID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/rso.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $RSOID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function createEvent($conn, $event_name, $event_desc, $event_date, $event_addr, $event_Stime, $event_Etime, $event_num, $event_email, $event_restriction, $RSOID) {
    try {
        $sql = "INSERT INTO events (Name, Description, Location, EventStartTime, EventEndTime, Date, ContactPhone, contact_email) VALUES (?, ? , ? ,?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("SQL statement preparation failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "ssssssss", $event_name, $event_desc, $event_addr, $event_Stime, $event_Etime, $event_date, $event_num, $event_email);
        mysqli_stmt_execute($stmt);
        $eventID = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        if ($event_restriction === "private") {
            $sql = "INSERT INTO private_event (EventID, Creator) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception("SQL statement preparation failed: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt, "ii", $eventID, $_SESSION["UID"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else if ($event_restriction === "public") {
            $sql = "INSERT INTO public_event (EventID, Creator) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception("SQL statement preparation failed: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt, "ii", $eventID, $_SESSION["UID"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else if ($event_restriction === "rso") {
            $sql = "INSERT INTO rso_event (EventID, RSOID) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception("SQL statement preparation failed: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt, "ii", $eventID, $RSOID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("location: ../php/events.php?error=none");
        exit();
    } catch (Exception $e) {
        header("location: ../php/events.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

function createUni($conn, $name, $address, $desc, $numofstudents, $pictures) {
    $sql = "INSERT INTO university (Name, Address, Description, NumOfStudents, Pictures, Creator) VALUES (?, ?, ? , ? ,?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/universities.php?error=stmtfailed");
        exit();
    }

   

    mysqli_stmt_bind_param($stmt, "sssisi", $name, $address, $desc, $numofstudents, $pictures, $_SESSION["UID"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $said = getValueFromTable($conn, "superadmin", "UID", $_SESSION["UID"], "ID");
    $sql = "INSERT INTO affiliated (UniversityName, SAID) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../php/universities.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "si", $name, $said);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../php/universities.php?error=none");
    exit();
}

function checkIfIdExists($conn, $id, $table) {
    $sql = "SELECT COUNT(*) AS id_count FROM $table WHERE UID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    return $id_count > 0;
}

function studentInRSO($conn, $UID, $RSOID) {
    $sql = "SELECT COUNT(*) AS id_count FROM rso_members WHERE UID = ? AND RSOID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $UID, $RSOID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($id_count > 0)
        return true;
    else
        return false;
}

function studentInUni($conn, $UID, $Name) {
    $sql = "SELECT COUNT(*) AS id_count FROM student_of WHERE UID = ? AND Name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $UID, $Name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($id_count > 0)
        return true;
    else
        return false;
}



function studentJoinRso($conn, $UID, $RSOID) {
    $sql = "INSERT INTO rso_members (UID, RSOID) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $UID, $RSOID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function studentLeavesRso($conn, $UID, $RSOID) {
    $sql = "DELETE FROM rso_members WHERE UID = ? AND RSOID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $UID, $RSOID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function checkIfRSOActive($conn, $RSOID) {
    $sql = "SELECT Status FROM rsos WHERE RSOID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $RSOID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $status);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $status;
}


function studentJoinUni($conn, $UID, $Name) {

    $sql = "SELECT COUNT(*) AS id_count FROM student_of WHERE UID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $UID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($id_count > 0) {
        $sql = "UPDATE student_of SET Name = ? WHERE UID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $Name, $UID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $sql = "INSERT INTO student_of (UID, Name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $UID, $Name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function findUniName($conn, $UID) {
    $sql = "SELECT NAME FROM student_of WHERE UID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $UID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    return $result;
}

function isOwner($conn, $UID) {
    $sql = "SELECT RSOID FROM rsos WHERE Owner = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $UID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    return $result;
}