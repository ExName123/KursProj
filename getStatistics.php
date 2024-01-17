<?php
require('connectToDB.php');

$sql = "CALL getCountFiresByYears()";
$result = $mysqli->query($sql);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);

$mysqli->close();
?>