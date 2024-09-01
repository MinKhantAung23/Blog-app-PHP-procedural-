<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'];
    $query = "SELECT image FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        $imageFile = "images/" . $post['image'];
        if (file_exists($imageFile)) {
            unlink($imageFile);
        } else {
            echo "Error deleting image file.";
        }

        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $result = $stmt->execute();

        if ($result) {
            echo "<script>alert('Post deleted successfully');</script>";
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting post from database: " . implode(" ", $stmt->errorInfo());
        }
    } else {
        echo "Post not found.";
    }
}
