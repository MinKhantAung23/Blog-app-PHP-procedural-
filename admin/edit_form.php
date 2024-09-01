<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :post_id");
$stmt->bindParam(':post_id', $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

?>

<?php include('header.html') ?>

<!-- Input Form Section -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Edit Post</h2>
    <form action="edit_post.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $result[0]['id'] ?>" />
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" id="title" name="title" value="<?= $result[0]['title'] ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
            <textarea type="text" id="content" name="content" class="shadow  appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required>
                <?= $result[0]['content'] ?>
            </textarea>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Post Image:</label>
            <img src="./images/<?= $result[0]['image'] ?>" class="shadow appearance-none border rounded w-24 h-24 mb-4" alt="post image">
            <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update post</button>
            <a href="index.php" class="border px-3 py-2 bg-gray-500 text-white rounded-md shadow hover:opacity-80 duration-300">Cancel</a>
        </div>
    </form>
</div>
<?php include('footer.html') ?>