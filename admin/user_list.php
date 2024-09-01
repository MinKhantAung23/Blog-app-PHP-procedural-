<?php
session_start();

require_once "../config/database.php";

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'] && $_SESSION['role'] == 1)) {
    header('Location: login.php');
}
?>

<?php
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (empty($_POST['search']) || isset($_POST['all_posts'])) {
    // total records from database
    $totalQuery = "SELECT COUNT(*) FROM users";
    $totalStmt = $pdo->prepare($totalQuery);
    $totalStmt->execute();
    $totalPosts = $totalStmt->fetchColumn();
    $totalPages = ceil($totalPosts / $limit);

    // paginated records from database
    $query = "SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $search = $_POST['search'];
    // total records from database
    $totalQuery = "SELECT COUNT(*) FROM users WHERE name LIKE :search OR email LIKE :search";
    $totalStmt = $pdo->prepare($totalQuery);
    $totalStmt->bindValue(':search', '%' . $search . '%');
    $totalStmt->execute();
    $totalPosts = $totalStmt->fetchColumn();
    $totalPages = ceil($totalPosts / $limit);

    $query = "SELECT * FROM users WHERE name LIKE :search ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<?php include('header.html'); ?>

<!-- Content Area -->
<main class="flex-1 p-6 bg-gray-100">

    <!-- Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold mb-4">Recent Users</h2>
        <div class="flex justify-between">
            <form method="POST" action="">
                <button type="submit" name="all_posts" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    All Users
                </button>
            </form>
            <a href="user_form.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add New User
            </a>
        </div>

        <table class="min-w-full bg-white border ruounded-lg shadow table mt-8 ">
            <thead class="table-header">
                <tr>
                    <th class="py-2 px-4 border-b">#</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">Role</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <?php
                if ($results) {
                    $i = 1;
                    foreach ($results as $result) { ?>
                        <tr>
                            <td class="py-2 px-4 border-b text-center"><?= $i ?></td>
                            <td class="py-2 px-4 border-b text-center"><?= $result['name'] ?></td>
                            <td class="py-2 px-4 border-b text-center"><?= $result['email'] ?></td>
                            <td class="py-2 px-4 border-b text-center"><?= ($result['role'] == 1) ? 'Admin' : 'User' ?></td>
                            <td class="py-2 px-4 border-b text-center">
                                <div class="flex gap-4 justify-center ">
                                    <a href="user_edit.php?id=<?= $result['id'] ?>" class="border px-3 py-2 bg-yellow-500 text-white rounded-md shadow hover:opacity-80 duration-300">Edit</a>
                                    <a href="user_delete.php?id=<?= $result['id'] ?>" onclick="return confirm('Are you sure you want to delete this?')" class=" border px-3 py-2 bg-red-500 text-white rounded-md shadow hover:opacity-80 duration-300">Delete</a>
                                </div>
                            </td>
                        </tr>
                <?php
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="pagination flex justify-end mt-4">

            <?php if ($page <= 1): ?>
                <button class=" px-3 py-2 mx-1 text-white bg-gray-500 rounded  cursor-not-allowed" disabled>Previous</button>
            <?php else: ?>
                <a href="?page=<?php echo $page - 1; ?>" class=" px-3 py-2 mx-1 text-white bg-gray-500 rounded hover:bg-blue-600">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-3 py-2 mx-1 rounded <?php echo $i === $page ? 'bg-blue-700 text-white' : 'bg-gray-200 text-black'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <?php if ($page >= $totalPages): ?>
                <button class=" px-3 py-2 mx-1 text-white bg-gray-500 rounded cursor-not-allowed" disabled>
                    Next
                </button>
            <?php else: ?>
                <a href="?page=<?php echo $page + 1; ?>" class=" px-3 py-2 mx-1 text-white bg-gray-500 rounded hover:bg-blue-600">
                    Next
                </a>
            <?php endif; ?>
        </div>

    </div>

</main>

<?php include('footer.html'); ?>