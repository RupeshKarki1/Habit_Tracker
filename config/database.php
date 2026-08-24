<?php

$host = "localhost";
$dbname = "habit_tracker";
$username = "root";
$password = "";

$connection = new mysqli($host, $username, $password, $dbname);

if($connection -> connect_error){
    die('Database connection failed.');
}

?>