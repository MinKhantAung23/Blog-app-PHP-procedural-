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
    if (empty($_POST['role'])) {
        $role = 0;
    } else {
        $role = 1;
    }

    if (!empty($name) && !empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email= :email";

        $stmt = $pdo->prepare($query);
        $user = $stmt->execute([
            ":email" => $email,
        ]);

        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<script>alert('Email already exists',window.location.href = 'user_form.php');</script>";
        } else {
            $query = "INSERT INTO users (name,email,password,role) VALUES (:name, :email,:password, :role)";
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute([
                ":name" => $name,
                ":email" => $email,
                ":password" => $password,
                ":role" => $role
            ]);

            if ($result) {
                echo "<script>alert('User added successfully',window.location.href = 'user_list.php');</script>";
            } else {
                echo "Error executing statement: " . implode(" ", $stmt->errorInfo());
            }
        }
    } else {
        $message = "Please fill in all fields and upload an image.";
    }
}
