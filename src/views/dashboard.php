<?php
// dashboard.php

use App\Session;
$currentUser = Session::get('user')['username'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
            body {
                background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%);
            }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-500 to-purple-600 flex flex-col">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg>
            <span class="text-2xl font-bold text-gray-800">POS Dashboard</span>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-gray-700 text-lg font-medium">
                <?php if ($currentUser): ?>
                    Welcome, <span class="font-bold text-indigo-600"><?php echo htmlspecialchars($currentUser); ?></span>
                <?php else: ?>
                    Welcome, Guest
                <?php endif; ?>
            </span>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-indigo-700 transition">Logout</button>
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center justify-center p-8">
        <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <svg class="w-12 h-12 text-blue-700 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3v6a1 1 0 001 1h6a1 1 0 001-1v-6h3a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1z"/></svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Sales</h3>
                <a href="#" class="mt-4 bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition text-center">New Sale</a>
            </div>
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <svg class="w-12 h-12 text-blue-700 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/></svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Inventory</h3>
                <a href="/inventory" class="mt-4 bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition text-center">Manage Inventory</a>
            </div>
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <svg class="w-12 h-12 text-blue-700 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2a4 4 0 10-8 0 4 4 0 008 0zm6 2a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Users</h3>
                <a href="#" class="mt-4 bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition text-center">Manage Users</a>
            </div>
        </div>
        <div class="w-full max-w-4xl mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                    <h4 class="text-lg font-bold text-blue-900 mb-2">Today's Sales</h4>
                <p class="text-3xl font-extrabold text-indigo-600">$0.00</p>
            </div>
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <h4 class="text-lg font-bold text-gray-700 mb-2">Inventory Items</h4>
                <p class="text-3xl font-extrabold text-indigo-600">0</p>
            </div>
        </div>
    </main>
        <footer class="text-center text-blue-900 py-6 text-sm font-semibold">
            &copy; 2025 Frozen Meatshop POS. All rights reserved.
        </footer>
</body>
</html>
