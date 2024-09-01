<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? 1 : 0;

    if (isset($id) && !empty($id)) {
        $query = "UPDATE users SET name=:name, email=:email, password=:password, role=:role WHERE id=:id";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":email" => $email,
            ":password" => $password,
            ":role" => $role
        ]);

        if ($result) {
            echo "<script>alert('User updated successfully'), window.location.href = 'user_list.php'</script>";
        } else {
            echo "Error executing statement: " . implode(" ", $stmt->errorInfo());
        }
    }

    exit();
}
