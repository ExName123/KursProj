<?php
// Подключение к базе данных
require('connectToDB.php');

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Подготовка SQL-запроса для вставки данных в таблицу wishes
    $sql = "INSERT INTO wishes (email, message) VALUES (?, ?)";

    // Создание подготовленного запроса
    $stmt = $mysqli->prepare($sql);

    // Привязка параметров
    $stmt->bind_param("ss", $email, $message);

    // Выполнение подготовленного запроса
    if ($stmt->execute()) {
    } else {
        echo "Ошибка при выполнении запроса: " . $stmt->error;
    }

    // Закрытие подготовленного запроса
    $stmt->close();
}

// Закрытие соединения с базой данных
$mysqli->close();
?>
