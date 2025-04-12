<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Genereer reset token
    $token = bin2hex(random_bytes(32));

    // Update de gebruiker met een reset token
    $stmt = $conn->prepare("UPDATE users SET reset_token = :token WHERE email = :email");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "Een reset link is naar je e-mail gestuurd.";
        // Hier kun je PHPMailer gebruiken om een reset link naar de gebruiker te sturen.
    } else {
        echo "Er is iets misgegaan.";
    }
}
?>

<form action="reset-password.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Wachtwoord Herstellen</button>
</form>
