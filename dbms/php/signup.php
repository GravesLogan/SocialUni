<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/signup.css">
    </head>
    <body>
        <h1>Sign Up</h1>

        <div class="navbar">
            <a href="signup.php" class="navbar-active">Sign Up</a>
            <a href="login.php" class="navbar-inactive">Log In</a>
        </div>

        <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyinput") {
                    echo "<h2>Please fill in the input fields.</h2>";
                } else if ($_GET["error"] == "invalidemail") {
                    echo "<h2>Please enter email in the correct format. Example: JohnDoe@example.com</h2>";
                } else if ($_GET["error"] == "mismatched") {
                    echo "<h2>Please check that both passwords match.</h2>";
                } else if ($_GET["error"] == "emailtaken") {
                    echo "<h2>The submitted email is already in use.</h2>";
                }

            }
        ?>

        <form action="../includes/signup_inc.php" method="post" class="signup_boxes">
            <div class="form_group">
                <label for="FirstName">Name: </label>
                <input type="text" name="FirstName" placeholder="Enter First Name">
            </div>
            <div class="form_group">
                <label for="Email">Email: </label>
                <input type="text" name="Email" placeholder="JohnDoe@ucf.edu">
            </div>
            <div class="form_group">
                <label for="Password">Password: </label>
                <input type="password" name="Password" placeholder="Enter Password">
            </div>
            <div class="form_group">
                <label for="PasswordRe">Repeat Password: </label>
                <input type="password" name="PasswordRe" placeholder="Enter Password">
            </div>
            <div>
                <label for="Role">Select which role best fits you:</label>
                <select id="Role" name="Role">
                    <option value="---">---</option>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div class="form_group">
                <button type="submit" name="submit" class="submit-button">Sign Up</button>
            </div>
        </form>
    </body>
</html>