<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}

echo "<pre>";
print_r($_GET);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'];
    if (isset($id) && !empty($id)) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $result = $stmt->execute();

        if ($result) {
            echo "<script>alert('Post deleted successfully', window.location.href = 'user_list.php');</script>";
        } else {
            echo "Error deleting post from database: " . implode(" ", $stmt->errorInfo());
        }
    } else {
        echo "Post not found.";
    }
}
