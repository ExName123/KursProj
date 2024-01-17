<?php
require('connectToDB.php');
header('Content-type: application/json');
$sql = "CALL getFiresByMonths()";

$result = $mysqli->query($sql);

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);

$mysqli->close();
?>