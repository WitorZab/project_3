<?php

$host = 'localhost';        
$dbname = 'databse';  
$username = 'root'; 
$password = ''; 


try {
   
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    echo "Verbonden met de database!";
} catch (PDOException $e) {
    
    echo "Verbinding mislukt: " . $e->getMessage();
}
?>