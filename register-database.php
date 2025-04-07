<?php

require "database/database.php";

$hash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);


$insert_user = $conn->prepare("INSERT INTO users (gebruikersnaam,wachtwoord) VALUES (:gebruikersnaam, :wachtwoord)");
$insert_user->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
$insert_user->bindParam(":wachtwoord", $hash);
$insert_user->execute();

 header("location: index.php");

 session_start(); // Start de sessie

if (!isset($_SESSION['userid'])) {
    echo "Je moet ingelogd zijn om een bericht te sturen.";
    exit(); // Stop de uitvoering van de code als de gebruiker niet ingelogd is.
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $content = $_POST['content'];
    $user_id = $_SESSION['userid']; //

   
    $stmt = $conn->prepare("INSERT INTO messages (content, user_id) VALUES (:content, :user_id)");
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    echo "Bericht is succesvol verstuurd!";
    header("Location: index.php"); 
    exit();
}
?>