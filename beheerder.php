// admin.php (beheerderspagina)
<?php
require 'database/database.php';


session_start();
if ($_SESSION['user_id'] !== 'admin') { 
    echo "Je hebt geen toegang tot deze pagina.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
