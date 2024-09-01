<?php
session_start();

require_once "./config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('Location: login.php');
}


$blogId = $_GET['id'];
$commentId = $_POST['comment_id'];

$query = "SELECT * FROM comments WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $commentId]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {

    if ($_SESSION['user_id'] == $comment['author_id'] || $_SESSION['role'] == 1) {
        $query = "DELETE FROM comments WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $commentId);
        $result = $stmt->execute();
        if ($result) {
            header("Location: post-detail.php?id=$blogId");
            exit();
        }
    } else {
        // header("Location: post-detail.php?id=$blogId");
        echo "<script>alert('You are not authorized to delete this comment.', window.location.href = 'post-detail.php?id=$blogId')</script>";
        exit();
    }
}
