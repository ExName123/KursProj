<?php
require("connectToDB.php");

$error_message = ""; 

if (!empty($_POST)) {
    $username = $_POST['username'];
    $result = mysqli_query($mysqli, "SELECT * FROM users WHERE username=\"" . $username . "\"");

    if (mysqli_num_rows($result) == 0) {
        mysqli_query($mysqli, "INSERT INTO users (username, password, name, user_role) VALUES (
            \"" . $username . "\",
            \"" . $_POST["password"] . "\",
            \"" . md5($_POST["name"]) . "\",
            \"" . "1" . "\"
        )");
        header("Location: index.php");
    } else {
        $error_message = "Username already exists. Please choose another username.";
        echo "<script>console.log('Debug Objects: " . $error_message . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        #error-message {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
    <form action="sign-up.php" method="post">
      
        <div id="error-message"><?php echo $error_message; ?></div>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required oninput="hideErrorMessage()">
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="name">Name:</label>
        <input id="name" name="name" required>
        <br>
        <button type="submit" name="register">Register</button>
        <a href="login.html">Login</a>
    </form>

    <script>
        var errorMessage = "<?php echo $error_message; ?>";
        if (errorMessage !== "") {
            document.getElementById('error-message').style.display = 'block';
        }
        function hideErrorMessage() {
            document.getElementById('error-message').style.display = 'none';
        }
    </script>
</body>
</html>
