<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pagina</title>
    <link rel="stylesheet" href="style.css">
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
        <p id="error-message" class="error"></p>
        <p>Heb je nog geen account? <a href="#">Registreer hier</a></p>
    </div>

    <?php
    // Controleer of het formulier is ingediend
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Includeer het database verbinding bestand
        require "database/database.php";
        
        // Controleer of zowel gebruikersnaam als wachtwoord zijn ingevuld
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Maak de SQL-query voorbereid om SQL-injecties te voorkomen
            $stmt = $conn->prepare("SELECT * FROM users WHERE gebruikersnaam = :username");
            $stmt->bindParam(":username", $_POST['username']);
            $stmt->execute();
            $users = $stmt->fetchAll();

            // Controleer of de gebruiker bestaat
            if (!$users) {
                echo "<p class='error'>Error: Gebruiker bestaat niet.</p>";
                exit;
            } 

            // Verifieer het wachtwoord
            $iscorrect = password_verify($_POST['password'], $users[0]['wachtwoord']);
            if (!$iscorrect) {
                echo "<p class='error'>Fout login! Verkeerd wachtwoord.</p>";
            } else {
                // Start de sessie en stel sessievariabelen in
                session_start();
                $_SESSION['username'] = $users[0]['gebruikersnaam'];
                // Redirect naar de homepage na een succesvolle login
                header("Location: index.php");
                exit;
            }
        } else {
            echo "<p class='error'>Vul zowel gebruikersnaam als wachtwoord in.</p>";
        }
    }
    ?>
</body>
</html>
