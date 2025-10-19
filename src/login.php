<?php
// login.php
// Simple login form with Tailwind CSS
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-200 via-white to-red-200">
    <div class="bg-white bg-opacity-95 shadow-2xl rounded-3xl p-10 w-full max-w-md animate-fade-in border-4 border-blue-200">
        <div class="flex flex-col items-center mb-6">
            <!-- Frozen Meatshop Icon -->
            <div class="flex items-center space-x-4 mb-2">
                <!-- Olaf JPEG -->
                <img src="/assets/Olaf%20Digital%20Download%20-%20Used%20for%20any%20program,%20Cricuit,%20PhotoShop,%20Google,%20Microsoft%20-%20PNG,.jpg" alt="Olaf from Frozen" style="width:56px; height:56px; object-fit:contain;   " loading="lazy">
                <!-- Meatshop Icon -->
                <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="32" cy="32" rx="28" ry="20" fill="#d32f2f"/>
                    <ellipse cx="32" cy="32" rx="20" ry="14" fill="#fff3e0"/>
                    <ellipse cx="32" cy="32" rx="14" ry="10" fill="#b71c1c"/>
                    <ellipse cx="32" cy="32" rx="7" ry="5" fill="#ffebee"/>
                </svg>
                <!-- Elsa SVG (stylized, simple) -->
                <img src="/assets/f42335844178bb1497255c0badc057c6.jpg" alt="Olaf from Frozen" style="width:56px; height:56px; object-fit:contain;   " loading="lazy">

            </div>
            <h2 class="text-3xl font-extrabold text-blue-900 tracking-wide text-center">J & J Frozen Meatshop POS Login</h2>
            <p class="text-blue-700 text-lg font-medium">Welcome! Please sign in to continue.</p>
        </div>
        <form action="/signin" method="post" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-bold text-blue-900">Username</label>
                <input type="text" name="username" id="username" required class="mt-1 block w-full px-4 py-2 border-2 border-blue-200 rounded-lg shadow-sm focus:ring-blue-700 focus:border-blue-700 bg-blue-50 placeholder:text-blue-300" placeholder="Enter your username">
            </div>
            <div>
                <label for="password" class="block text-sm font-bold text-blue-900">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full px-4 py-2 border-2 border-blue-200 rounded-lg shadow-sm focus:ring-blue-700 focus:border-blue-700 bg-blue-50 placeholder:text-blue-300" placeholder="Enter your password">
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-700 focus:ring-blue-700 border-blue-200 rounded">
                    <label for="remember" class="ml-2 block text-sm text-blue-900">Remember me</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-700 hover:text-blue-900">Forgot your password?</a>
                </div>
            </div>
            <button type="submit" class="w-full flex justify-center py-3 px-6 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white bg-gradient-to-r from-blue-700 to-red-700 hover:from-blue-900 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition">Sign in</button>
        </form>
        <p class="mt-6 text-center text-gray-600">Don't have an account? <a href="/signup" class="text-blue-700 hover:underline font-bold">Signup</a></p>
        <p class="mt-6 text-center text-blue-900 text-xs font-semibold">&copy; 2025 Frozen Meatshop POS. All rights reserved.</p>
    </div>
    <script>
        // Simple fade-in animation
        document.querySelector('.animate-fade-in').style.opacity = 0;
        setTimeout(() => {
            document.querySelector('.animate-fade-in').style.transition = 'opacity 1s';
            document.querySelector('.animate-fade-in').style.opacity = 1;
        }, 100);
    </script>
</body>
</html>
