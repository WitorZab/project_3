<?php

session_start();
require "database/database.php";
var_dump($_SESSION);

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
} else if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$user_id = (int) $_SESSION['user_id'];  


require "database/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Ongeldige CSRF-token.");
    }

    if (isset($_POST['gebruikersnaam']) && isset($_POST['wachtwoord'])) {
        $gebruikersnaam = trim($_POST['gebruikersnaam']);
        $wachtwoord = trim($_POST['wachtwoord']);

        if (!empty($wachtwoord)) {
            $wachtwoord_hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET gebruikersnaam = :gebruikersnaam, wachtwoord = :wachtwoord WHERE id = :user_id");
            $stmt->bindParam(':gebruikersnaam', $gebruikersnaam, PDO::PARAM_STR);
            $stmt->bindParam(':wachtwoord', $wachtwoord_hash, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            // Alleen gebruikersnaam updaten
            $stmt = $conn->prepare("UPDATE users SET gebruikersnaam = :gebruikersnaam WHERE id = :user_id");
            $stmt->bindParam(':gebruikersnaam', $gebruikersnaam, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Profiel succesvol bijgewerkt!";
        } else {
            echo "Geen wijzigingen aangebracht.";
        }
    }
}


$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        
        echo "Welkom, " . htmlspecialchars($user['gebruikersnaam']);
    } else {
        echo "Geen gebruiker gevonden.";
    }
} catch (PDOException $e) {
    echo "Fout bij uitvoeren query: " . $e->getMessage();
    exit;
}

?>
<?php include "partials/header.php"; ?>



    
</header> 

<main> 
    <div class="profile-container">
        <h1>Welkom, <?= htmlspecialchars($user['gebruikersnaam']) ?>!</h1>

        <div class="profile-info">
            <p><strong>Gebruikersnaam:</strong> <?= htmlspecialchars($user['gebruikersnaam']) ?></p>
          
        </div>



        <h2>Profiel bewerken</h2>
        <form method="post">
              
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="input-group">
                <label for="gebruikersnaam">Nieuwe gebruikersnaam</label>
                <input type="text" name="gebruikersnaam" id="gebruikersnaam" value="<?= htmlspecialchars($user['gebruikersnaam']) ?>" required>
            </div>
            <div class="input-group">
                <label for="wachtwoord">Nieuw wachtwoord</label>
                <input type="password" name="wachtwoord" id="wachtwoord">
            </div>
            <button type="submit" class="btn">Profiel bijwerken</button>
        </form>
    </div>
</main>

</body>
</html>
