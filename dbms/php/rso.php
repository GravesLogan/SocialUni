<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RSO's</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/rso.css">
    </head>
    <body>
        <h1>RSO's</h1>

        <div class="navbar">
            <a href="login.php" class="navbar-inactive">Log Out</a>
            <a href="home.php" class="navbar-inactive">Home</a>
            <a href="rso.php" class="navbar-active">RSOs</a>
            <a href="events.php" class="navbar-inactive">Events</a>
            <a href="universities.php" class="navbar-inactive">Universities</a>
        </div>
        <?php
            require_once '../includes/dbc_inc.php';
            require_once '../includes/functions_inc.php';

            $isStudent = true;
            if (checkIfIdExists($conn, $_SESSION["UID"], "admin") || checkIfIdExists($conn, $_SESSION["UID"], "superadmin")) {
                    $isStudent = false;
            } 
        ?>


        <form action="../includes/create_rso_inc.php" method="post" class="signup_boxes">
            <div class="form_group">
                <label for="rso_name">RSO Name: </label>
                <input type="text" class="textbox1" name="rso_name" placeholder="Enter RSO Name">
            </div>
            <div class="form_group">
                <button type="submit" name="submit" class="submit-button">Create RSO</button>
            </div>
        </form>
        
        
        <form id="RSOSearch" class="rso_lookup" method="POST">
            <label for="RSOSearch">Search for RSO name: </label>
            <input type="text" class="textbox2" id="RSOSearch" name="RSOSearch" placeholder="enter here...">
            <button type="submit" class="submit-button">Search</button>
        </form>

        <?php
            // Connect to the database
            $conn = mysqli_connect("localhost", "root", "", "testdatabase");
            $searchText = "";


            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            // Grabs the value of the email search
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve data from the form
                $searchText = $_POST['RSOSearch'];
            }

            $emailDomain = explode('@', getValueFromTable($conn, 'users', 'UID', $_SESSION['UID'], 'Email'));
            $emailDomain = $emailDomain[1];

            

            // Retrieve data from the database
            $sql = "SELECT rsos.* FROM rsos JOIN users ON rsos.Owner = users.UID WHERE SUBSTRING_INDEX(users.Email, '@', -1) = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $emailDomain);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if any rows were returned
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row

                $value = getValueFromTable($conn, "admin", "UID", $_SESSION["UID"], "ID");

                echo "<table>";
                echo "<thead><tr><th>RSOID</th><th>RSO Name</th><th>Status</th><th></th><th></th><th></th></tr></thead>";
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row["Name"] === $searchText) {
                        echo "<tr>";
                        echo "<td>" . $row["RSOID"] . "</td>";
                        echo "<td>" . $row["Name"] . "</td>";
                        if (intval($row["Owner"]) === $_SESSION["UID"] && $row["Status"] === 'active') {
                            echo "<td>" . $row["Status"] . "</td>";
                            echo '<td><form action="events.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="RSOID" value="'. $row["RSOID"] .'">
                                        <button type="submit" name="submit" class="submit-button">Add events</button>
                                    </div>
                                </form></td>
                                <td><form action="../includes/delete_rso_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="rso_name" value="'. $row["Name"] .'">
                                        <button type="submit" name="submit" class="submit-button">Delete RSO</button>
                                    </div>
                                </form></td>';
                        } else {
                            echo "<td>" . $row["Status"] . "</td>";
                            echo "<td></td>";
                        }
                        if (studentInRSO($conn, $_SESSION["UID"], $row["RSOID"]) === false && intval($row["Owner"]) !== $_SESSION["UID"]) {
                        echo '<td><form action="../includes/join_rso_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="RSOID" value="'. $row["RSOID"] .'">
                                        <button type="submit" name="submit" class="submit-button">Join RSO</button>
                                    </div>
                                </form></td>';
                        } else if (studentInRSO($conn, $_SESSION["UID"], $row["RSOID"]) === true && intval($row["Owner"]) !== $_SESSION["UID"]){
                            echo'<td><form action="../includes/leave_rso_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="RSOID" value="'. $row["RSOID"] .'">
                                        <button type="submit" name="submit" class="remove-button">Leave RSO</button>
                                    </div>
                                </form></td>';
                        }
                        echo "</tr>";
                    } else if ($searchText === "") {
                        echo "<tr>";
                        echo "<td>" . $row["RSOID"] . "</td>";
                        echo "<td>" . $row["Name"] . "</td>";
                        if (intval($row["Owner"]) === $_SESSION["UID"]) {
                            echo "<td>" . $row["Status"] . "</td>";
                            echo '<td><form action="../includes/delete_rso_inc.php" method="post" class="signup_boxes">
                                        <div class="form_group">
                                            <input type="hidden" name="rso_name" value="'. $row["Name"] .'">
                                            <button type="submit" name="submit" class="remove-button">Delete RSO</button>
                                        </div>
                                    </form></td>';
                            if ($row["Status"] === 'active') {
                                echo '<td><form action="events.php" method="post" class="signup_boxes">
                                            <div class="form_group">
                                                <input type="hidden" name="RSOID" value="'. $row["RSOID"] .'">
                                                <button type="submit" name="submit" class="submit-button">Add events</button>
                                            </div>
                                        </form></td>';
                            }
                                
                        } else {
                            echo "<td>" . $row["Status"] . "</td>";
                            echo "<td></td>";
                        }
                        if (studentInRSO($conn, $_SESSION["UID"], $row["RSOID"]) === false) {
                        echo '<td><form action="../includes/join_rso_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="RSOID" value="'. $row["RSOID"] .'">
                                        <button type="submit" name="submit" class="submit-button">Join RSO</button>
                                    </div>
                                </form></td>';
                        } else if (studentInRSO($conn, $_SESSION["UID"], $row["RSOID"]) === true && intval($row["Owner"]) !== $_SESSION["UID"]){
                            echo'<td><form action="../includes/leave_rso_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="RSOID" value="'. $row["RSOID"] .'">
                                        <button type="submit" name="submit" class="remove-button">Leave RSO</button>
                                    </div>
                                </form></td>';
                        }
                        echo "</tr>";
                    }
                }
            echo "</table>";
            } else {
                echo "0 results";
            }

            // Close the connection
            mysqli_close($conn);
        ?>
    </body>
</html>