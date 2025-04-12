<?php
require 'database/database.php';
session_start();

// Controleer of de gebruiker ingelogd is en een admin is
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['beheerder'] != 1) {
    echo "Je hebt geen toegang tot deze pagina.";
    exit;
}

// Haal alle gebruikers op uit de database
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Gebruikers - Chirpify</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Beheer Gebruikers</h2>
<table>
    <tr>
        <th>Gebruikersnaam</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['gebruikersnaam']) ?></td>
        <td>
            
            
            <form action="delete-user.php" method="post">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <button type="submit">Verwijderen</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

