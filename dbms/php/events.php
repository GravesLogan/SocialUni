<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Events</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/events.css">
    </head>
    <body>
        <h1>Events</h1>

        <div class="navbar">
            <a href="login.php" class="navbar-inactive">Log Out</a>
            <a href="home.php" class="navbar-inactive">Home</a>
            <a href="rso.php" class="navbar-inactive">RSOs</a>
            <a href="events.php" class="navbar-active">Events</a>
            <a href="universities.php" class="navbar-inactive">Universities</a>
            <?php
                require_once '../includes/dbc_inc.php';
                require_once '../includes/functions_inc.php';
            ?>
        </div>

        <?php
        $isStudent = true;
        if (checkIfIdExists($conn, $_SESSION["UID"], "admin") || checkIfIdExists($conn, $_SESSION["UID"], "superadmin") || isOwner($conn, $_SESSION["UID"]) !== NULL) {
            echo'<form action="../includes/create_event_inc.php" method="post" class="signup_boxes">
                <div class="form_group">
                    <label for="event_name">Event name: </label>
                    <input type="text" class="textbox1" name="event_name" placeholder="Enter event name">
                </div>
                <div class="form_group">
                    <label for="event_desc">Event description: </label>
                    <input type="text" class="textbox1" name="event_desc" placeholder="Enter event description">
                </div>
                <div class="form_group">
                    <label for="event_addr">Event location: </label>
                    <input type="text" class="textbox1" name="event_addr" id="addressInput" placeholder="Selected Address" readonly>
                </div>
                <div class="form_group">
                    <label for="event_stime">Event start time: </label><br>
                    <input type="time" id="event_start_time" name="event_start">
                </div>
                <div class="form_group">
                    <label for="event_stime">Event end time: </label><br>
                    <input type="time" id="event_end_time" name="event_end">
                </div>
                <div class="form_group">
                    <label for="date">Select a date:</label>
                    <input type="date" name="event_date">
                </div>
                <div class="form_group">
                    <label for="event_number">Contact phone number: </label>
                    <input type="text" class="textbox1" name="event_number" placeholder="Enter contact\'s phone number">
                </div>
                <div class="form_group">
                    <label for="event_email">Contact email: </label>
                    <input type="text" class="textbox1" name="event_email" placeholder="Enter contact\'s email">
                </div>
                <div class="form_group">
                    <label for="restriction">Who should attend: </label>';
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["RSOID"])){
                            echo '<input type="hidden" class="textbox1" name="RSOID" value='. $_POST["RSOID"].'>
                            <input type="hidden" class="textbox1" name="restriction" value="rso">';
                        } else {
                            echo '<select class="textbox1" name="restriction" placeholder="Choose the event restriction">
                                <option value="">---</option>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>';
                        }
                echo '</div>
                <div class="form_group">
                    <button type="submit" name="submit" class="submit-button">Create Event</button>
                </div>
            </form>';
                $isStudent = false;
                // Check if there's an error message in the URL
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error !== "none") {
                // Display the error message
                echo '<h2><div class="error-message">' . htmlspecialchars($error) . '</div></h2>';
            }
        }

        echo '<div id="map"></div>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAo_NnumlH6am1ESfhJ5Y6nD9PmYNh2LvI&callback=initMap" async defer></script>
                    <script src="../map.js"></script>';
        }
        ?>


        

        <form id="EventSearch" class="event_lookup" method="POST">
            <label for="EventSearch">Search for Event name: </label>
            <input type="text" class="textbox2" id="EventSearch" name="EventSearch" placeholder="enter here...">
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
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['EventSearch'])) {
                // Retrieve data from the form
                $searchText = $_POST['EventSearch'];
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['AdminID']) && !empty($_POST['AdminID'])) {
                // Retrieve data from the form
                $AdminID = $_POST['AdminID'];
            }


            // Retrieve data from the database
            $sql = "SELECT * FROM events";
            $result = mysqli_query($conn, $sql);

            // Check if any rows were returned
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                echo "<table>";
                echo "<thead><tr><th>Event Name</th><th>Description</th><th>Start time</th><th>End time</th><th>Contact Phone</th><th>Contact Email</th><th>Reviews</th></tr></thead>";
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
            echo "</table>";
            } else {
                echo "0 results";
            }

            // Close the connection
            mysqli_close($conn);
        ?>
    </body>
</html>