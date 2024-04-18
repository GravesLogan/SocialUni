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
        <link rel="stylesheet" href="../css/universities.css">
    </head>
    <body>
        <h1>Universities</h1>
        <div class="navbar">
            <a href="login.php" class="navbar-inactive">Log Out</a>
            <a href="home.php" class="navbar-inactive">Home</a>
            <a href="rso.php" class="navbar-inactive">RSOs</a>
            <a href="events.php" class="navbar-inactive">Events</a>
            <a href="universities.php" class="navbar-active">Universities</a>
            <?php
                require_once '../includes/dbc_inc.php';
                require_once '../includes/functions_inc.php';
            ?>
        </div>

        
        <?php
            $_SESSION['SAID'] = getValueFromTable($conn, "superadmin", "UID", $_SESSION["UID"], "ID");

            $isStudent = true;
            if (checkIfIdExists($conn, $_SESSION["UID"], "superadmin")) {
                $sql = "SELECT COUNT(*) AS count FROM Affiliated WHERE SAID = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    // Handle the SQL error
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["SAID"]); // Assuming UID is an integer
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    if ($row['count'] > 0) {
                        ?>

                        <?php
                    } else {
                        ?>
                        <?php
                            echo '<form action="../includes/create_uni_inc.php" method="post" enctype="multipart/form-data" class="signup_boxes">
                            <div class="form_group">
                                <label for="uni_name">University name: </label>
                                <input type="text" class="textbox1" name="uni_name" placeholder="Enter university name">
                            </div>
                            <div class="form_group">
                                <label for="uni_address">University address: </label>
                                <input type="text" class="textbox1" name="uni_adderss" id="addressInput" placeholder="Selected Address" readonly>
                            </div>
                            <div class="form_group">
                                <label for="uni_desc">University description: </label>
                                <input type="text" class="textbox1" name="uni_desc" placeholder="Enter description">
                            </div>
                            <div class="form_group">
                                <label for="uni_students">Number of students: </label>
                                <input type="text" class="textbox1" name="uni_students" placeholder="Enter the amount of students">
                            </div>
                            <div class="form_group">
                                <label for="uni_pics">Upload a picture: </label>
                                <input type="file" class="textbox1" name="uni_pics" accept="image/*" placeholder="Drag file here">
                            </div>
                            <div class="form_group">
                                <button type="submit" name="submit" class="submit-button">Create University</button>
                            </div>
                        </form>
                        <div id="map"></div>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAo_NnumlH6am1ESfhJ5Y6nD9PmYNh2LvI&callback=initMap" async defer></script>
                        <script src="../map.js"></script>';
                        ?>
                        
                        <?php
                    }
                }
                $isStudent = false;
            }
        ?>
       


        

        
        

        <form id="EventSearch" class="event_lookup" method="POST">
            <label for="EventSearch">Search for University name: </label>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve data from the form
                $searchText = $_POST['EventSearch'];
            }

            // Retrieve data from the database
            $sql = "SELECT * FROM university";
            $result = mysqli_query($conn, $sql);

            // Check if any rows were returned
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                echo "<table>";
                echo "<thead><tr><th>Name</th><th>Address</th><th>Description</th><th>NumOfStudents</th><th>Pictures</th><th></th></tr></thead>";
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row["Name"] === $searchText) {
                        echo "<tr>";
                        echo "<td>" . $row["Name"] . "</td>";
                        echo "<td>" . $row["Address"] . "</td>";
                        echo "<td>" . $row["Description"] . "</td>";
                        echo "<td>" . $row["NumOfStudents"] . "</td>";
                        echo "<td>" . $row["Pictures"] . "</td>";
                        if ($isStudent === true) {
                            if (studentInUni($conn, $_SESSION["UID"], $row["Name"]) === false) {
                                echo '<td><form action="../includes/join_student_of_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="Name" value="'. $row["Name"] .'">
                                        <button type="submit" name="submit" class="submit-button">Join university</button>
                                    </div>
                                </form></td>';
                            } else {
                                echo "<td></td>";
                            }
                            
                        }
                        echo "</tr>";
                    } else if ($searchText === "") {
                        echo "<tr>";
                        echo "<td>" . $row["Name"] . "</td>";
                        echo "<td>" . $row["Address"] . "</td>";
                        echo "<td>" . $row["Description"] . "</td>";
                        echo "<td>" . $row["NumOfStudents"] . "</td>";
                        echo '<td><img src="' . $row['Pictures'] . '" alt="Image"></td>';
                        if ($isStudent === true) {
                            if (studentInUni($conn, $_SESSION["UID"], $row["Name"]) === false) {
                                echo '<td><form action="../includes/join_student_of_inc.php" method="post" class="signup_boxes">
                                    <div class="form_group">
                                        <input type="hidden" name="Name" value="'. $row["Name"] .'">
                                        <button type="submit" name="submit" class="submit-button">Join university</button>
                                    </div>
                                </form></td>';
                            } else {
                                echo "<td></td>";
                            }
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