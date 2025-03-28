<?php

$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=localhost;dbname=chirpefy", $username, $password);
}catch (PDOException $e){
    echo $e->getMessage();
}