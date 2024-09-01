<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];
    $author_id = $_SESSION['user_id'];

    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];

    if (!empty($title) && !empty($content) && !empty($image_name)) {

        $saveFolder = "images";

        if (!is_dir($saveFolder)) {
            mkdir($saveFolder, 0777);
        }
        $imageType = pathinfo($image_name, PATHINFO_EXTENSION);
        $saveImage = uniqid() . $image_name;
        $saveFileName = $saveFolder . "/" . $saveImage;

        if (move_uploaded_file($image_tmp_name, $saveFileName)) {
            $query = "INSERT INTO posts (title,content,image,author_id) VALUES (:title, :content, :image, :author_id)";
            $stmt = $pdo->prepare($query);

            $result = $stmt->execute([
                ":title" => $title,
                ":content" => $content,
                ":image" => $saveImage,
                ":author_id" => $author_id
            ]);

            if ($result) {
                echo "<script>alert('New post added successfully');</script>";
            } else {
                echo "Error executing statement: " . implode(" ", $stmt->errorInfo());
            }
        }
    } else {
        $message = "Please fill in all fields and upload an image.";
    }
    header('Location: index.php');
    exit();
}
