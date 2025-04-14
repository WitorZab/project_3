<?php
session_start();
require "database/database.php";  // Zorg ervoor dat je verbinding maakt met de database
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pagina</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="login-container">
        <h2>Inloggen</h2>
        <form id="loginForm" method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Gebruikersnaam</label>
                <input type="text" id="username" name="username" placeholder="Vul je gebruikersnaam in" required>
            </div>
            <div class="input-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" placeholder="Vul je wachtwoord in" required>
            </div>
            <button type="submit" class="btn">Inloggen</button>
        </form>
        <nav class="navbarinfo">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Inloggen</a></li>
                <li><a href="register-form.php">Registreren</a></li>
                <li><a href="informatie.php">Informatie</a></li>
                <li><a href="berichten.php">Berichten</a>
            </ul>
        </nav>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        ?>
        <p>Heb je nog geen account? <a href="#">Registreer hier</a></p>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Voer de databasequery uit om de gebruiker op te halen
            $stmt = $conn->prepare("SELECT * FROM users WHERE gebruikersnaam = :username");
            $stmt->bindParam(":username", $_POST['username']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);  

            if (!$user) {
                $_SESSION['error_message'] = 'Fout: Gebruiker bestaat niet.';
                header("Location: login.php");
                exit;
            }

            // Controleer of het wachtwoord correct is
            $iscorrect = password_verify($_POST['password'], $user['wachtwoord']);
            if (!$iscorrect) {
                $_SESSION['error_message'] = 'Fout login! Verkeerd wachtwoord.';
                header("Location: login.php");
                exit;
            } else {
                // Sla de gebruikersgegevens op in de sessie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];
                $_SESSION['rol'] = $user['rol']; // Sla ook de rol op

                // Controleer of de gebruiker een admin is
                if ($user['rol'] === 'admin') {
                    // Als de gebruiker een admin is, stuur dan door naar de admin pagina
                    header("Location: beheerder.php");
                    exit;
                } else {
                    // Als de gebruiker geen admin is, stuur dan naar de homepagina
                    header("Location: index.php");
                    exit;
                }
            }
        } else {
            $_SESSION['error_message'] = 'Vul zowel gebruikersnaam als wachtwoord in.';
            header("Location: login.php");
            exit;
        }
    }
    ?>
</body>
</html>
