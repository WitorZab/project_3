<?php
session_start();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pagina</title>
    <link rel="stylesheet" href="main.css">
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
        
        require "database/database.php";
        
        
        if (isset($_POST['username']) && isset($_POST['password'])) {
           
            $stmt = $conn->prepare("SELECT * FROM users WHERE gebruikersnaam = :username");
            $stmt->bindParam(":username", $_POST['username']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);  

            
            if (!$user) {
                $_SESSION['error_message'] = 'Fout: Gebruiker bestaat niet.';
                header("Location: login.php");
                exit;
            } 

           
            $iscorrect = password_verify($_POST['password'], $user['wachtwoord']);
            if (!$iscorrect) {
                $_SESSION['error_message'] = 'Fout login! Verkeerd wachtwoord.';
                header("Location: login.php");
                exit;
            } else {
                
                $_SESSION['user_id'] = $user['gebruikersnaam'];
               
                header("Location: index.php");
                exit;
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
