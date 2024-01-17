<?php
require('connectToDB.php');
// Получение параметров из POST-запроса
$p_year = $_POST['year'];
$p_month = $_POST['month'];
$p_type_id = $_POST['fireType'];

$sql = "SELECT id, lat, lon, type_id, dt FROM points 
        WHERE YEAR(dt) = ? AND MONTH(dt) = ? AND type_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $p_year, $p_month, $p_type_id);

$stmt->execute();

$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$mysqli->close();

echo json_encode($data);
?>