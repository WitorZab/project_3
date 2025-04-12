<?php 
session_start();
include "partials/header.php";
require "database/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $content]);
    }
}

// Handle like functionality
if (isset($_POST['like_post'])) {
    $post_id = $_POST['like_post'];
    $stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
    $stmt->execute([$post_id]);
}

// Get all posts
$stmt = $conn->prepare("
    SELECT posts.*, users.gebruikersnaam 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Social Media</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .container {
            display: flex;
            max-width: 900px;
            margin: 20px auto;
        }
        .sidebar {
            width: 25%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        .feed {
            width: 50%;
            padding: 20px;
        }
        .profile {
            width: 25%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .post {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        }
        .post h3 {
            margin: 0 0 10px;
        }
        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .post-form {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        }
        .post-form textarea {
            width: 100%;
            height: 60px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .post-form button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </div>
        <div class="feed">
            <div class="post-form-container">
                <form action="page.php" method="POST" class="post-form">
                    <textarea 
                        name="content" 
                        placeholder="What's happening?"
                        maxlength="280"
                        required
                    ></textarea>
                    <button type="submit" class="btn btn-primary">Tweet</button>
                </form>
            </div>
            <div class="posts-feed">
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <div class="post-header">
                            <h3 class="post-author"><?= htmlspecialchars($post['gebruikersnaam']) ?></h3>
                            <span class="post-time"><?= date('M d', strtotime($post['created_at'])) ?></span>
                        </div>
                        <p class="post-content"><?= htmlspecialchars($post['content']) ?></p>
                        <div class="post-actions">
                            <form action="page.php" method="POST" class="like-form">
                                <input type="hidden" name="like_post" value="<?= $post['id'] ?>">
                                <button type="submit" class="like-button">
                                    ❤️ <?= $post['likes'] ?? 0 ?>
                                </button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="profile">
            <img src="profile-pic.jpg" alt="Profile Picture">
            <h3>John Doe</h3>
            <p>Web Developer | Blogger</p>
        </div>
    </div>
</body>
</html>

<?php include "partials/footer.php"; ?>