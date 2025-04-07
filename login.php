<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p id="error-message" class="error"></p>
        <p>Don't have an account? <a href="#">Register here</a></p>
    </div>

    <?php
    // var_dump($_POST);
// echo "test1";
require "database/database.php";
$stmt = $conn->prepare("SELECT * FROM users WHERE gebruikersnaam =:username");
$stmt->bindParam(":username", $_POST['username']);
$stmt->execute();
$users = $stmt->fetchAll();
// echo "test2";
if (!$users) {
    echo "Error: Gebruiker bestaat niet.";
    // var_dump($users);
    exit;
} else {
    echo "Gebruiker gevonden:";
    // var_dump($users);
}
//check doen of user bestaat
    //zo niet, error message
//bestaat wel

// echo "test3";

$iscorrect = password_verify($_POST['password'], $users[0]['wachtwoord']);

if(!$iscorrect){
    echo "FOUT LOGIN";
}
else{
    session_start();
    $_SESSION['username'] = $users[0]['gebruikersnaam'];
    // $_SESSION['userid'] = $users[0]['id'];
    // header("location: index.php");
    echo "SUCCESVOL INGELOGD";
}


?>

    
</body>
</html>