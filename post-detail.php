<?php
session_start();

require_once "./config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('Location: login.php');
}

date_default_timezone_set('Asia/Yangon');

$id = $_GET['id'];
$query = "SELECT * FROM posts WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

$blogId = $_GET['id'];

$cmtQuery = "SELECT comments.id AS comment_id, comments.content AS comment_content, comments.created_at, users.name AS author_name
    FROM comments
    INNER JOIN users ON comments.author_id = users.id
    WHERE comments.post_id = :post_id
    ORDER BY comments.created_at DESC";
$cmtStmt = $pdo->prepare($cmtQuery);
$cmtStmt->execute(['post_id' => $blogId]);
$comments = $cmtStmt->fetchAll(PDO::FETCH_ASSOC);


if ($_POST) {
    $comment = $_POST['comment'];

    $query = "INSERT INTO comments (content, author_id, post_id) VALUES (:content, :author_id, :post_id )";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':content', $comment);
    $stmt->bindValue(':author_id', $_SESSION['user_id']);
    $stmt->bindValue(':post_id', $blogId);
    $result = $stmt->execute();

    if ($result) {
        header('Location: post-detail.php?id=' . $blogId);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post['title'] ?> - My Blog</title>
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
        <section class="container mx-auto flex-grow mt-10 p-4 bg-white shadow-lg rounded-lg">
            <article class="prose lg:prose-xl max-w-none mx-auto h-full w-[60%]">
                <h1 class="text-4xl font-bold mb-4"><?= $post['title'] ?></h1>
                <div class="text-gray-600 mb-6">
                    <span>Posted at : <?= date('F j, Y', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="content mb-6">
                    <img src="<?= "./admin/images/" . $post['image'] ?>" alt="<?= $post['title'] ?>" class="w-full h-80 mb-4 rounded-md shadow-sm">
                    <p><?= $post['content'] ?></p>
                </div>

                <!-- Comments Section -->
                <section class="mt-10 border-t-2 border-gray-300">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold mb-6">Comments</h2>
                        <a class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" href="index.php">back to posts</a>
                    </div>
                    <!-- Display Comments -->
                    <?php if (!empty($comments)) { ?>
                        <div class="space-y-6">
                            <?php foreach ($comments as $comment) { ?>
                                <div class="flex items-start space-x-4">

                                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                        <?= strtoupper(substr($comment['author_name'], 0, 1)) ?>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg shadow-md flex-1">
                                        <div class="flex justify-between items-center mb-2">
                                            <div>
                                                <h4 class="text-lg font-semibold"><?= $comment['author_name'] ?></h4>
                                                <span class="text-gray-500 text-sm"><?= date('F j, Y', strtotime($comment['created_at'])) ?></span>
                                            </div>
                                            <form action="comment_delete.php?id=<?= $blogId ?>" method="POST">
                                                <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this?')" class="text-red-500 hover:underline hover:text-red-700">
                                                    delete
                                                </button>
                                            </form>
                                        </div>
                                        <p class="text-gray-800"><?= $comment['comment_content'] ?></p>

                                    </div>

                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <p class="text-gray-600">No comments yet. Be the first to comment!</p>
                    <?php } ?>

                    <!-- Add a Comment Form -->
                    <div class="mt-10">
                        <h3 class="text-xl font-bold mb-4">Leave a Comment</h3>
                        <form action="" method="POST" class="space-y-4">
                            <input type="hidden" name="post_id" value="<?= $postId ?>">
                            <div>
                                <label class="block text-gray-700 mb-2" for="comment">Comment:</label>
                                <textarea id="comment" name="comment" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </section>
            </article>
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