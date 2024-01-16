<?php
$mysqli;
try{
    define('DB_HOST', 'bababuy0.beget.tech'); //Адрес
    define('DB_USER', 'bababuy0_kurs'); //Имя пользователя
    define('DB_PASSWORD', 'Aa12345678'); //Пароль
    define('DB_NAME', 'bababuy0_kurs'); //Имя БД
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

}
catch(Exception $e){
    die('Ошибка подключения к базе данных');
}
?>
