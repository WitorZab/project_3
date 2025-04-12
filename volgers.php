<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['follow_user_id'])) {
    $followed_id = $_POST['follow_user_id'];
    $follower_id = $_SESSION['userid'];

    $stmt = $conn->prepare("INSERT INTO followers (follower_id, followed_id) VALUES (:follower_id, :followed_id)");
    $stmt->bindParam(':follower_id', $follower_id);
    $stmt->bindParam(':followed_id', $followed_id);
    $stmt->execute();

    header("Location: profile.php?user_id={$followed_id}");
    exit();
}
