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
        <link rel="stylesheet" href="../css/reviews.css">
    </head>
    <body>
        <h1>Social Uni</h1>

        <div class="navbar">
            <a href="login.php" class="navbar-inactive">Log Out</a>
            <a href="home.php" class="navbar-inactive">Home</a>
            <?php
                require_once '../includes/dbc_inc.php';
                require_once '../includes/functions_inc.php';

                if (checkIfIdExists($conn, $_SESSION["UID"], "admin")) {
                    echo '<a href="rso.php" class="navbar-inactive">RSOs</a>';
                    echo '<a href="events.php" class="navbar-active">Events</a>';
                }
                else if (checkIfIdExists($conn, $_SESSION["UID"], "superadmin")) {
                    echo '<a href="rso.php" class="navbar-inactive">RSOs</a>';
                    echo '<a href="events.php" class="navbar-active">Events</a>';
                    echo '<a href="universities.php" class="navbar-inactive">Universities</a>';
                }
            ?>
        </div>
        <form action="events.php" method="post">
            <div class="return_button">
                <button type="submit" name="submit" class="submit-button">Return</button>
            </div>
        </form>
        
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
                $_SESSION["EventID"] = $_POST["id"];
            }
        ?>

        <form action="../includes/create_reviews_inc.php" method="post" class="signup_boxes">
            <div class="form_group">
                <input type="hidden" name="EventID" value="<?php echo $_SESSION["EventID"]; ?>">
            </div>
            <div class="form_group">
                <label for="rating">Event rating: </label>
                <input type="text" class="textbox1" name="rating" placeholder="Enter rating">
            </div>
            <div class="form_group">
                <label for="comment">Event comment: </label>
                <input type="text" class="textbox1" name="comment" placeholder="Enter comment">
            </div>
            <div class="form_group">
                <button type="submit" name="submit" class="submit-button">Submit review</button>
            </div>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
                // Retrieve data from the form
                $EventID = $_POST['id'];
            }

            // Retrieve data from the database
            $sql = "SELECT * FROM reviews";
            $result = mysqli_query($conn, $sql);

            // Check if any rows were returned
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                echo "<table>";
                echo "<thead><tr><th>UID</th><th>Rating</th><th>Comment</th><th>Time</th></tr></thead>";
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row["EventID"] === $_SESSION["EventID"]) {
                        echo "<tr>";
                        echo "<td>" . $row["UID"] . "</td>";
                        echo "<td>" . $row["Rating"] . "</td>";
                        echo "<td>" . $row["Comments"] . "</td>";
                        echo "<td>" . $row["ReviewTime"] . "</td>";
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