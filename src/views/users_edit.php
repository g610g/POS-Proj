<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify User - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-200 via-white to-red-200 flex flex-col">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg>
            <span class="text-2xl font-bold text-gray-800">Modify User</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="/src/views/users.php" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-blue-900 transition">Back to Users</a>
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div class="bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 w-full max-w-md mt-10">
            <h2 class="text-3xl font-extrabold text-center text-indigo-700 mb-6">Modify User</h2>
            <form action="/src/controllers/users_edit.php" method="POST" class="space-y-6">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
                <div>
                    <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" required class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-700 to-red-700 text-white py-3 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Save Changes</button>
            </form>
        </div>
    </main>
    <footer class="bg-white bg-opacity-95 text-gray-700 text-center py-6 mt-16 border-t-4 border-blue-200">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between">
            <span class="font-bold text-blue-900 text-lg">Frozen Meatshop Inventory</span>
            <span class="text-sm mt-2 md:mt-0">&copy; <?php echo date('Y'); ?> Frozen Meatshop Inventory. All rights reserved.</span>
            <a href="mailto:support@example.com" class="text-blue-700 hover:underline text-sm">Contact Support</a>
        </div>
    </footer>
</body>
</html>
