<?php
session_start();
require 'database/database.php';

include 'partials/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Ongeldige CSRF-token.";
    } else {
        $content = trim($_POST['content']);

        if (!empty($content)) {
            $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

            
            $stmt = $conn->prepare("INSERT INTO messages (user_id, content, likes) VALUES (?, ?, 0)");
            $stmt->execute([$user_id, $content]);

            $_SESSION['success_message'] = "Je bericht is geplaatst!";
        } else {
            $_SESSION['error_message'] = "Bericht mag niet leeg zijn.";
        }
    }

    header("Location: berichten.php"); 
    exit();
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $message_id = $_GET['delete'];

    $stmt = $conn->prepare("SELECT user_id FROM messages WHERE id = ?");
    $stmt->execute([$message_id]);
    $message = $stmt->fetch();

    if ($message && $message['user_id'] == $user_id) {
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->execute([$message_id]);

        $_SESSION['success_message'] = "Je bericht is verwijderd.";
    } else {
        $_SESSION['error_message'] = "Je mag alleen je eigen berichten verwijderen.";
    }

    header("Location: berichten.php"); 
    exit();
}


if (isset($_GET['like']) && is_numeric($_GET['like'])) {
    $message_id = $_GET['like'];

   
    $stmt = $conn->prepare("UPDATE messages SET likes = likes + 1 WHERE id = ?");
    $stmt->execute([$message_id]);

    $_SESSION['success_message'] = "Je hebt het bericht geliked!";
    header("Location: berichten.php");
    exit();
}


$stmt = $conn->prepare("SELECT gebruikersnaam FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();


$stmt = $conn->prepare("
    SELECT 
        m.id, 
        m.content, 
        m.user_id, 
        m.likes,
        u.gebruikersnaam
    FROM 
        messages m
    JOIN 
        users u ON m.user_id = u.id
    ORDER BY 
        m.id DESC
");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

   
    <h2>Plaats een nieuw bericht</h2>
    <form action="berichten.php" method="POST">
        <textarea name="content" rows="4" cols="50" placeholder="Wat wil je delen?" required></textarea><br>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Bericht plaatsen</button>
    </form>

    <h2>Alle berichten</h2>

    <?php if (empty($messages)): ?>
        <p>Geen berichten gevonden.</p>
    <?php else: ?>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <p><?php echo htmlspecialchars($message['content']); ?></p>
                <p>Geplaatst door: <?php echo htmlspecialchars($message['gebruikersnaam']); ?></p>
                <p>Likes: <?php echo $message['likes']; ?></p>

                
                <a href="berichten.php?like=<?php echo $message['id']; ?>" class="like-button">Like</a>

               
                <?php if ($message['user_id'] == $user_id): ?>
                    
                    <form action="berichten.php" method="GET" onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?');">
                        <input type="hidden" name="delete" value="<?php echo $message['id']; ?>">
                        <button type="submit" class="delete-button">Verwijderen</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="index.php">Terug naar home</a></p>

</body>
</html>
