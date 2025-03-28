<?php include "partials/header.php"; ?>
<body>
    <div class="container">


<div class="container">
    <div class="registration-container">
        <h1>Log in</h1>
        <form action="register-database.php" method="post">
            <div class="input-group">
                <label for="gebruikersnaam">Uw gebruikersnaam</label>
                <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Johndoe">
            </div>
            <div class="input-group">
                <label for="wachtwoord">Uw wachtwoord</label>
                <input type="password" name="wachtwoord" id="wachtwoord">
            </div>
            <button type="submit" class="btn">Maak nu een account aan</button>
        </form>
    </div>
</div>

        <!-- existing login container -->
    </div>
</body>

<?php include "partials/footer.php"; ?>

