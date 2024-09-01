<?php include('header.html') ?>

<!-- Input Form Section -->
<div class="bg-white flex-grow p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Add New Post</h2>
    <form action="add_post.php" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
            <textarea type="text" id="content" name="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Post Image:</label>
            <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add post</button>
            <a href="index.php" class="border px-3 py-2 bg-gray-500 text-white rounded-md shadow hover:opacity-80 duration-300">Cancel</a>
        </div>
    </form>
</div>
<?php include('footer.html') ?>