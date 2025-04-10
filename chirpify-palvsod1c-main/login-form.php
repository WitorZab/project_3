<?php include "partials/header.php"; ?>
<link rel="stylesheet" href="style.css">
<body>
    <div class="container">

        <div class="container">
            <div class="registration-container">
                <h1>Log in</h1>

                <!-- Display error message if present -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="input-group">
                        <label for="gebruikersnaam">Uw gebruikersnaam</label>
                        <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Johndoe" required>
                    </div>
                    <div class="input-group">
                        <label for="wachtwoord">Uw wachtwoord</label>
                        <input type="password" name="wachtwoord" id="wachtwoord" required>
                    </div>
                    <button type="submit" class="btn">Log in</button>
                </form>
            </div>
        </div>


        <!-- existing login container -->
    </div>
</body>

<?php include "partials/footer.php"; ?>
