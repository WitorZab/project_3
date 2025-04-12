// delete-user.php
<?php
require 'database/database.php';
session_start();


if ($_SESSION['userid'] !== 'admin') {
    echo "Je hebt geen toegang tot deze actie.";
    exit;
}

if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    echo "De gebruiker is succesvol verwijderd.";
    header("Location: admin.php"); 
    exit();
}
?>
