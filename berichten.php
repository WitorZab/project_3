<?php
session_start();
require 'database/database.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Ongeldige CSRF-token.";
    } else {
        $content = trim($_POST['content']);

        if (!empty($content)) {
            $stmt = $conn->prepare("INSERT INTO messages (user_id, content, likes) VALUES (?, ?, 0)");
            if ($stmt->execute([$user_id, $content])) {
                $_SESSION['success_message'] = "Je bericht is geplaatst!";
            } else {
                $_SESSION['error_message'] = "Er ging iets mis bij het plaatsen van je bericht.";
            }
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
    $message_id = (int)$_GET['like'];
    
    // Begin transaction
    $conn->beginTransaction();
    try {
        // Check if message exists
        $check_msg = $conn->prepare("SELECT id FROM messages WHERE id = ?");
        $check_msg->execute([$message_id]);
        if (!$check_msg->fetch()) {
            throw new Exception("Message not found");
        }

        // Check for existing like
        $check_like = $conn->prepare("SELECT id FROM likes WHERE message_id = ? AND user_id = ?");
        $check_like->execute([$message_id, $user_id]);
        
        if (!$check_like->fetch()) {
            // Add like
            $like_stmt = $conn->prepare("INSERT INTO likes (message_id, user_id) VALUES (?, ?)");
            $like_stmt->execute([$message_id, $user_id]);
            
            // Update message like count
            $update_stmt = $conn->prepare("UPDATE messages SET likes = (SELECT COUNT(*) FROM likes WHERE message_id = ?) WHERE id = ?");
            $update_stmt->execute([$message_id, $message_id]);
            
            $conn->commit();
            $_SESSION['success_message'] = "Je hebt het bericht geliked!";
        } else {
            $conn->rollBack();
            $_SESSION['error_message'] = "Je hebt dit bericht al geliked!";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error_message'] = "Er ging iets mis met liken.";
    }
    
    header("Location: berichten.php");
    exit();
}

if (isset($_GET['unlike']) && is_numeric($_GET['unlike'])) {
    $message_id = $_GET['unlike'];
    
    // Begin transaction
    $conn->beginTransaction();
    try {
        // Remove specific like
        $delete_stmt = $conn->prepare("DELETE FROM likes WHERE message_id = ? AND user_id = ?");
        $delete_stmt->execute([$message_id, $user_id]);
        
        // Update ONLY this message's like count
        $update_stmt = $conn->prepare("UPDATE messages SET likes = (SELECT COUNT(*) FROM likes WHERE message_id = ?) WHERE id = ?");
        $update_stmt->execute([$message_id, $message_id]);
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error_message'] = "Er ging iets mis met unliken.";
    }
    
    header("Location: berichten.php");
    exit();
}

$stmt = $conn->prepare("SELECT gebruikersnaam FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $conn->prepare("
    SELECT 
        m.*, 
        u.gebruikersnaam,
        (SELECT COUNT(*) FROM likes WHERE message_id = m.id) as like_count,
        EXISTS(SELECT 1 FROM likes WHERE message_id = m.id AND user_id = ?) as user_liked
    FROM 
        messages m
    LEFT JOIN 
        users u ON m.user_id = u.id
    ORDER BY 
        m.id DESC
");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berichten</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Berichten</h1>
    <nav class="navbarinfo">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Inloggen</a></li>
                <li><a href="register-form.php">Registreren</a></li>
                <li><a href="informatie.php">Informatie</a></li>
            </ul>
        </nav>

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
                <p>Likes: <?php echo $message['like_count']; ?></p>

                <?php if ($message['user_liked']): ?>
                    <a href="berichten.php?unlike=<?php echo $message['id']; ?>" class="unlike-button">Unlike</a>
                <?php else: ?>
                    <a href="berichten.php?like=<?php echo $message['id']; ?>" class="like-button">Like</a>
                <?php endif; ?>

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
