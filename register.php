<?php
session_start();
require_once "./config/database.php";

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email= :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<script>alert('Email already exists');</script>";
        } else {
            $query = "INSERT INTO users (name,email,password) VALUES (:name, :email, :password )";
            $stmt = $pdo->prepare($query);

            $result = $stmt->execute([
                ":name" => $name,
                ":password" => $password,
                ":email" => $email
            ]);

            if ($result) {
                echo "<script>alert('Registration successfully');</script>";
            } else {
                echo "Error executing statement: " . implode(" ", $stmt->errorInfo());
            }
        }
    } else {
        echo "<script>alert('Please fill in all fields');</script>";
    };
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - M Blog</title>
    <link href="./app.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-6">Register</h1>
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700" for="name">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="email">Email:</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="password">Password:</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-lg">Register</button>
        </form>
        <p class="mt-4 text-sm text-gray-600">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login</a></p>
    </div>

</body>

</html>