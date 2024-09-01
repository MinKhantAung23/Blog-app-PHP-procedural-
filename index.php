<?php
session_start();

require_once "./config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('Location: login.php');
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 1 && $_SESSION['logged_in'];

$query = "SELECT * FROM posts ORDER BY id DESC ";
$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M Blog</title>
    <link href="./app.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <main class="bg-gray-100 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-700 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="index.php" class="text-white text-3xl font-bold">M Blog</a>
                <nav>
                    <a href="index.php" class="text-white hover:text-gray-200 ml-4">Home</a>
                    <?php
                    if ($isAdmin) {
                        echo '<a href="admin/index.php" class="text-white hover:text-gray-200 ml-4">Admin</a>';
                    }
                    ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="text-white border border-white mx-4 hover:bg-gray-600 px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="text-white border border-white mx-4 hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <section class="container mx-auto mt-10 flex-grow">
            <h1 class="text-3xl font-bold mb-8">Latest Posts</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                if ($results) {

                    foreach ($results as $post) {
                ?>
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="<?= "./admin/images/" . $post['image'] ?>" alt="<?= $post['title'] ?>" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-2"><?= $post['title'] ?></h2>
                                <p class="text-gray-600"><?= substr($post['content'], 0, 100) ?>...</p>
                                <a href="post-detail.php?id=<?= $post['id'] ?>" class="text-blue-600 hover:underline mt-4 block">Read More</a>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-10 p-4">
            <div class="container mx-auto text-center">
                <p>&copy; 2024 My Blog. All rights reserved.</p>
            </div>
        </footer>

    </main>

</body>

</html>