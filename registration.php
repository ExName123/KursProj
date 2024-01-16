<?php
require("connectToDB.php");

$error_message = ""; // Initialize an error message variable

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
        // Set the error message if the username already exists
        $error_message = "Username already exists. Please choose another username.";
        header("Location: register.php");
    }
}
?>