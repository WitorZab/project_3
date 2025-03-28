<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="logo">Chirpify</a>
                <ul class="nav-links">
                    <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Home</a></li>
                    <li><a href="login-form.php" class="<?= basename($_SERVER['PHP_SELF']) === 'login-form.php' ? 'active' : '' ?>">Login</a></li>
                    <li><a href="register-form.php" class="<?= basename($_SERVER['PHP_SELF']) === 'register-form.php' ? 'active' : '' ?>">Register</a></li>
                    <li><a href="page.php" class="<?= basename($_SERVER['PHP_SELF']) === 'page.php' ? 'active' : '' ?>">Main page</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>