<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-indigo-500 to-purple-600">
    <!-- Navbar -->
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg>
            <span class="text-2xl font-bold text-gray-800">J & J Frozen POS System</span>
        </div>
        <div>
            <a href="/login" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Login Admin</a>
        </div>
    </nav>
    <!-- Hero Section -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-4">
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mt-16 mb-6 drop-shadow-lg">Welcome J & J Frozen POS System</h1>
        <p class="text-xl md:text-2xl text-gray-700 mb-10 max-w-2xl">Streamline your sales, manage inventory, and grow your business with our easy-to-use Point of Sale system.</p>
        <a href="/shop" class="inline-block bg-gradient-to-r from-blue-700 to-red-700 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:from-blue-900 hover:to-red-800 transition">Shop Now</a>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-4xl">
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <svg class="w-12 h-12 text-blue-700 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3v6a1 1 0 001 1h6a1 1 0 001-1v-6h3a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1z"/></svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Sales</h3>
                <p class="text-gray-600">Quickly process transactions and track sales in real time.</p>
            </div>
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <svg class="w-12 h-12 text-blue-700 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/></svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Inventory</h3>
                <p class="text-gray-600">Easily manage your products and stock levels.</p>
            </div>
            <div class="bg-white bg-opacity-90 rounded-2xl shadow-xl p-8 flex flex-col items-center hover:scale-105 transition">
                <svg class="w-12 h-12 text-blue-700 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2a4 4 0 10-8 0 4 4 0 008 0zm6 2a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Users</h3>
                <p class="text-gray-600">Control access and manage user roles securely.</p>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-white bg-opacity-95 text-gray-700 text-center py-6 mt-16 border-t-4 border-blue-200">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between">
            <span class="font-bold text-indigo-600 text-lg">POS System</span>
            <span class="text-sm mt-2 md:mt-0">&copy; <?php echo date('Y'); ?> POS System. All rights reserved.</span>
            <a href="mailto:support@example.com" class="text-blue-700 hover:underline text-sm">Contact Support</a>
        </div>
    </footer>
</body>
</html>
