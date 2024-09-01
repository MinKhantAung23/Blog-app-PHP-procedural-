<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = trim($_POST['content']);
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];

    if (isset($image) && !empty($image_name)) {
        $saveFolder = "images";

        if (!is_dir($saveFolder)) {
            mkdir($saveFolder, 0777);
        }
        $imageType = pathinfo($image_name, PATHINFO_EXTENSION);
        $saveImage = uniqid() . $image_name;
        $saveFileName = $saveFolder . "/" . $saveImage;

        if (move_uploaded_file($image_tmp_name, $saveFileName)) {
            $query = "UPDATE posts SET title=:title, content=:content, image=:image WHERE id=:id";
            $stmt = $pdo->prepare($query);

            $result = $stmt->execute([
                ":id" => $id,
                ":title" => $title,
                ":content" => $content,
                ":image" => $saveImage,

            ]);

            if ($result) {
                echo "<script>alert('Post updated successfully');</script>";
            } else {
                echo "Error executing statement: " . implode(" ", $stmt->errorInfo());
            }
        }
    } else {
        $query = "UPDATE posts SET title=:title, content=:content WHERE id=:id";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            ":id" => $id,
            ":title" => $title,
            ":content" => $content,
        ]);

        if ($result) {
            echo "<script>alert('Post updated successfully');</script>";
        } else {
            echo "Error executing statement: " . implode(" ", $stmt->errorInfo());
        }
    }
    header('Location: index.php');
    exit();
}
