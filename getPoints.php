<?php
require('connectToDB.php');
// Получение параметров из POST-запроса
$p_year = $_POST['year'];
$p_month = $_POST['month'];
$p_type_id = $_POST['fireType'];

// Подготовка SQL-запроса
$sql = "SELECT id, lat, lon, type_id, dt FROM points 
        WHERE YEAR(dt) = ? AND MONTH(dt) = ? AND type_id = ?";

// Подготовка запроса с использованием подготовленных выражений
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $p_year, $p_month, $p_type_id);

// Выполнение запроса
$stmt->execute();

// Получение результатов
$result = $stmt->get_result();

// Формирование массива данных
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Закрытие соединения и выходной вывод данных в JSON-формате
$stmt->close();
$mysqli->close();

echo json_encode($data);
?>