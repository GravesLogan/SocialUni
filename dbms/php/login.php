<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body>
        <h1>Log In</h1>

        <div class="navbar">
            <a href="signup.php" class="navbar-inactive">Register</a>
            <a href="login.php" class="navbar-active">Log In</a>
        </div>

        <form action="../includes/login_inc.php" method="post" class="signup_boxes">
            <div class="form_group">
                <label for="Email">Email: </label>
                <input type="text" name="Email" placeholder="JohnDoe@ucf.edu">
            </div>
            <div class="form_group">
                <label for="Password">Password: </label>
                <input type="password" name="Password" placeholder="Enter Password">
            </div>
            <div class="form_group">
                <button type="submit" name="submit" class="submit-button">Login</button>
            </div>
        </form>

        <?php
            


            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyinput") {
                    echo "<p2>Please fill in the input fields.</p2>";
                } else if ($_GET["error"] == "invalidemail") {
                    echo "<p2>Please enter email in the correct format. Example: JohnDoe@example.com</p2>";
                }
            }
        ?>

    </body>
</html>