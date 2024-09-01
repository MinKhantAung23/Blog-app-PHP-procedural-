<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

?>

<?php include('header.html') ?>

<!-- Input Form Section -->
<div class="bg-white p-6 flex-grow rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Edit Post</h2>
    <form action="user_update.php" method="POST">
        <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" value="<?= $result[0]['name'] ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" value="<?= $result[0]['email'] ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
            <input type="password" id="password" name="password" value="<?= $result[0]['password'] ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="role" class=" text-gray-700 text-sm font-bold mb-2">Admin:</label>
            <input type="checkbox" name="role" id="role" value="1" <?= $result[0]['role'] == 1 ? 'checked' : '' ?>>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update User</button>
            <a href="user_list.php" class="border px-3 py-2 bg-gray-500 text-white rounded-md shadow hover:opacity-80 duration-300">Cancel</a>
        </div>
    </form>
</div>
<?php include('footer.html') ?>