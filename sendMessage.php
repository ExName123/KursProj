<?php
require('connectToDB.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $message = $_POST['message'];

    $sql = "INSERT INTO wishes (email, message) VALUES (?, ?)";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $email, $message);

        if ($stmt->execute()) {
        } else {
            echo "Ошибка при выполнении запроса: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Ошибка при подготовке запроса: " . $mysqli->error;
    }
}

$mysqli->close();
?>