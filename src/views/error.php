<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-indigo-500 to-purple-600">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg>
            <span class="text-2xl font-bold text-gray-800">POS System</span>
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div class="bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 w-full max-w-md mt-10 text-center">
            <h1 class="text-5xl font-extrabold text-red-700 mb-4">Oops!</h1>
            <p class="text-xl text-gray-700 mb-8">An error has occurred. Please try again later.</p>
            <a href="/src/views/dashboard.php" class="inline-block bg-gradient-to-r from-blue-700 to-red-700 text-white px-8 py-3 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Go to Dashboard</a>
        </div>
    </main>
    <footer class="bg-white bg-opacity-95 text-gray-700 text-center py-6 mt-16 border-t-4 border-blue-200">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between">
            <span class="font-bold text-indigo-600 text-lg">POS System</span>
            <span class="text-sm mt-2 md:mt-0">&copy; <?php echo date('Y'); ?> POS System. All rights reserved.</span>
            <a href="mailto:support@example.com" class="text-blue-700 hover:underline text-sm">Contact Support</a>
        </div>
    </footer>
</body>
</html>
