<?php include('header.html') ?>

<!-- Input Form Section -->
<div class="bg-white flex-grow p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Add New User</h2>
    <form action="user_add.php" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
            <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="role" class=" text-gray-700 text-sm font-bold mb-2">Admin:</label>
            <input type="checkbox" name="role" id="role" value="1" checked>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add User</button>
            <a href="user_list.php" class="border px-3 py-2 bg-gray-500 text-white rounded-md shadow hover:opacity-80 duration-300">Cancel</a>
        </div>
    </form>
</div>
<?php include('footer.html') ?>