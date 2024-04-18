<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Social Uni</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css">
    </head>
    <body>
        <h1>Social Uni</h1>

        <div class="navbar">
            <a href="login.php" class="navbar-inactive">Log Out</a>
            <a href="home.php" class="navbar-active">Home</a>
            <a href="rso.php" class="navbar-inactive">RSOs</a>
            <a href="events.php" class="navbar-inactive">Events</a>
            <a href="universities.php" class="navbar-inactive">Universities</a>
            <?php
                require_once '../includes/dbc_inc.php';
                require_once '../includes/functions_inc.php';  
            ?>
        </div>

        <form id="eventName" class="email_lookup" method="POST">
            <label for="eventName">Find events by name: </label>
            <input type="text" id="eventName" name="eventName" placeholder="Event name">
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
                $searchText = $_POST['eventName'];
            }

            // Retrieve data from the database
            $sql = "SELECT * FROM events WHERE NOT EXISTS (SELECT 1 FROM private_event WHERE private_event.EventID = events.EventID) AND NOT EXISTS(SELECT 1 FROM rso_event WHERE rso_event.EventID = events.EventID)";
            $result = mysqli_query($conn, $sql);

            // Check if any rows were returned
            if (mysqli_num_rows($result) >= 0) {
                // Output data of each row
                echo "<table>";
                echo "<h3>Public events:</h3>";
                echo "<thead><tr><th>Event Name</th><th>Description</th><th>Start time</th><th>End time</th><th>Contact Phone</th><th>Contact Email</th><th>Reviews</th></tr></thead>";
                echo "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row["Name"] === $searchText) {
                            echo "<tr>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["EventStartTime"] . "</td>";
                            echo "<td>" . $row["EventEndTime"] . "</td>";
                            echo "<td>" . $row["ContactPhone"] . "</td>";
                            echo "<td>" . $row["contact_email"] . "</td>";
                            echo '<td><form action="reviews.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="id" value="' . $row['EventID'] . '">
                                        <button type="submit" name="submit" class="submit-button">See Reviews</button>
                                    </div>
                                </form></td>';
                            echo "</tr>";
                        } else if ($searchText === "") {
                            echo "<tr>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["EventStartTime"] . "</td>";
                            echo "<td>" . $row["EventEndTime"] . "</td>";
                            echo "<td>" . $row["ContactPhone"] . "</td>";
                            echo "<td>" . $row["contact_email"] . "</td>";
                            echo '<td><form action="reviews.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="id" value="' . $row['EventID'] . '">
                                        <button type="submit" name="submit" class="submit-button">See Reviews</button>
                                    </div>
                                </form></td>';
                            echo "</tr>";
                        }
                    }
                }
            echo "</table>";
            }

            
            $UniName = findUniName($conn, $_SESSION["UID"]);
            // Retrieve data from the database
            $sql = "SELECT * 
                FROM events 
                WHERE EventID IN (SELECT EventID FROM private_event) 
                AND Location IN (SELECT Address FROM university WHERE Name = ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $UniName);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);


            // Check if any rows were returned
            if (mysqli_num_rows($result) >= 0) {
                // Output data of each row
                echo "<table>";
                echo "<h3>Private events:</h3>";
                echo "<thead><tr><th>Event Name</th><th>Description</th><th>Start time</th><th>End time</th><th>Contact Phone</th><th>Contact Email</th><th>Reviews</th></tr></thead>";
                echo "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row["Name"] === $searchText) {
                            echo "<tr>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["EventStartTime"] . "</td>";
                            echo "<td>" . $row["EventEndTime"] . "</td>";
                            echo "<td>" . $row["ContactPhone"] . "</td>";
                            echo "<td>" . $row["contact_email"] . "</td>";
                            echo '<td><form action="reviews.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="id" value="' . $row['EventID'] . '">
                                        <button type="submit" name="submit" class="submit-button">See Reviews</button>
                                    </div>
                                </form></td>';
                            echo "</tr>";
                        } else if ($searchText === "") {
                            echo "<tr>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["EventStartTime"] . "</td>";
                            echo "<td>" . $row["EventEndTime"] . "</td>";
                            echo "<td>" . $row["ContactPhone"] . "</td>";
                            echo "<td>" . $row["contact_email"] . "</td>";
                            echo '<td><form action="reviews.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="id" value="' . $row['EventID'] . '">
                                        <button type="submit" name="submit" class="submit-button">See Reviews</button>
                                    </div>
                                </form></td>';
                            echo "</tr>";
                        }
                    }
                }
            echo "</table>";
            }

            // Retrieve data from the database
            $sql = "SELECT * FROM events WHERE EventID IN (SELECT EventID FROM rso_event WHERE EXISTS(SELECT RSOID FROM rso_members WHERE UID = ? AND rso_members.RSOID = rso_event.RSOID))";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $_SESSION["UID"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if any rows were returned
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                echo "<table>";
                echo "<h3>RSO events:</h3>";
                echo "<thead><tr><th>Event Name</th><th>Description</th><th>Start time</th><th>End time</th><th>Contact Phone</th><th>Contact Email</th><th>Reviews</th></tr></thead>";
                echo "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $tempRSOID = getValueFromTable($conn, 'rso_event', 'EventID', $row['EventID'], 'RSOID');
                    if (checkIfRSOActive($conn, $tempRSOID) == 'active') {
                        if ($row["Name"] === $searchText) {
                            echo "<tr>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["EventStartTime"] . "</td>";
                            echo "<td>" . $row["EventEndTime"] . "</td>";
                            echo "<td>" . $row["ContactPhone"] . "</td>";
                            echo "<td>" . $row["contact_email"] . "</td>";
                            echo '<td><form action="reviews.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="id" value="' . $row['EventID'] . '">
                                        <button type="submit" name="submit" class="submit-button">See Reviews</button>
                                    </div>
                                </form></td>';
                            echo "</tr>";
                        } else if ($searchText === "") {
                            echo "<tr>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["EventStartTime"] . "</td>";
                            echo "<td>" . $row["EventEndTime"] . "</td>";
                            echo "<td>" . $row["ContactPhone"] . "</td>";
                            echo "<td>" . $row["contact_email"] . "</td>";
                            echo '<td><form action="reviews.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="id" value="' . $row['EventID'] . '">
                                        <button type="submit" name="submit" class="submit-button">See Reviews</button>
                                    </div>
                                </form></td>';
                            echo "</tr>";
                        }
                    }
                }
            echo "</table>";
            }

            // Close the connection
            mysqli_close($conn);
        ?>
    </body>
</html>