<?php
require("connectToDB.php");

$result = mysqli_query($mysqli, "SELECT * FROM users WHERE
    username='".$_POST["login"]."' AND
    password='".$_POST["password"]."'
");

if(!$result || mysqli_num_rows($result) == 0){
	echo "Такой пользователь не существует.";
	exit;
}

session_start();
$_SESSION["user"] = mysqli_fetch_assoc($result);

header("Location: index.php");

?>