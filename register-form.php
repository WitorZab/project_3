<?php require "partials/header.php"; ?>
<h1>Registratie formulier</h1>
<form action="register-database.php" method="post">
    <label for="gebruikersnaam">Uw gebruikersnaam</label>
    <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Johndoe">
    <label for="wachtwoord">Uw wachtwoord</label>
    <input type="password" name="wachtwoord" id="wachtwoord">
    <input type="submit" value="Maak nu een account aan">
</form>

<?php require "partials/footer.php"; ?>

