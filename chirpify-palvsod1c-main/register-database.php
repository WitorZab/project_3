<?php

include "database/database.php";

// Het wachtwoord wordt gehashed voor veilige opslag
$hash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);

// De query is aangepast naar de juiste tabelnaam 'users'
$insert_user = $conn->prepare("INSERT INTO users (gebruikersnaam, wachtwoord) VALUES (:gebruikersnaam, :wachtwoord)");
$insert_user->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
$insert_user->bindParam(":wachtwoord", $hash);
$insert_user->execute();

session_start(); 
 
 if (!isset($_SESSION['userid'])) {
     echo "Je moet ingelogd zijn om een bericht te sturen.";
     exit(); 
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
 header("location: index.php");
 ?>


