<?php

include "database/database.php";

$hash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);


$insert_user = $conn->prepare("INSERT INTO usesrs (gebruikersnaam,wachtwoord) VALUES (:gebruikersnaam, :wachtwoord)");
$insert_user->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
$insert_user->bindParam(":wachtwoord", $hash);
$insert_user->execute();

header("location: index.php");


