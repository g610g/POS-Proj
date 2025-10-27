<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts Management - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-200 via-white to-red-200 flex flex-col">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg>
            <span class="text-2xl font-bold text-gray-800">User Accounts Management</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="/add-user" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-blue-900 transition">Add User</a>
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div class="w-full max-w-4xl bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 mt-10 border-2 border-blue-200">
            <h2 class="text-2xl font-extrabold text-blue-900 mb-6 tracking-wide">User List</h2>
            <div class="flex justify-end mb-4">
                <a href="/add-user" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:from-blue-900 hover:to-red-800 transition-all duration-200">Add User</a>
            </div>
            <div class="overflow-x-auto rounded-2xl shadow-lg">
                <table class="min-w-full bg-white rounded-2xl overflow-hidden border-2 border-blue-100">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-700 to-red-700 text-white">
                            <th class="px-8 py-4 text-left text-sm font-bold uppercase tracking-wider">Username</th>
                            <th class="px-8 py-4 text-left text-sm font-bold uppercase tracking-wider">Email</th>
                            <th class="px-8 py-4 text-center text-sm font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example row, replace with dynamic data -->
                         <?php foreach ($users as $user):?>
                        <?php
                            $params = [
                              'username' => $user['username'],
                              'email'    => $user['email'],
                              'id'       => $user['id'],
                            ];
                            $url = "/users/edit?" . http_build_query($params);
                            
                        ?>
                        <tr class="hover:bg-blue-50 transition-all duration-150">
                            <td class="px-8 py-4 whitespace-nowrap text-blue-900 font-semibold border-b border-blue-100"><?php echo $user['username']?></td>
                            <td class="px-8 py-4 whitespace-nowrap text-red-700 font-bold border-b border-blue-100"><?php echo $user['email']?></td>
                            <td class="px-8 py-4 whitespace-nowrap text-center border-b border-blue-100">
                                <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8');?>" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-5 py-2 rounded-xl font-bold shadow hover:from-blue-900 hover:to-red-800 transition-all duration-150 mr-2">Modify</a>
                                <a href="/users/delete?user_id=<?php echo urlencode(htmlspecialchars($user['id'])); ?>" class="bg-gradient-to-r from-red-700 to-blue-700 text-white px-5 py-2 rounded-xl font-bold shadow hover:from-red-900 hover:to-blue-800 transition-all duration-150">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <!-- Add dynamic rows here -->
                    </tbody>
                </table>
            </div>
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
