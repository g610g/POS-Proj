<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - POS System</title>
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
            <span class="text-2xl font-bold text-gray-800">J & J Frozen Meathshop POS System</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="/login" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-4 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Login</a>
        </div>
    </nav>
    <!-- Signup Form -->
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div class="bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 w-full max-w-md mt-10">
            <h2 class="text-3xl font-extrabold text-center text-indigo-700 mb-6">Create an Account</h2>
            <form action="/signup" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border <?php echo errors('username') ? 'border-red-500' : 'border-blue-200'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php if (errors('username')): ?>
                        <p class="text-red-500"><?php echo errors('username')?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-2 border <?php echo errors('email') ? 'border-red-500' : 'border-blue-200'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php if (errors('email')): ?>
                        <p class="text-red-500"><?php echo errors('email')?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-2 border <?php echo errors('password') ? 'border-red-500' : 'border-blue-200'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php if (errors('password')): ?>
                        <p class="text-red-500"><?php echo errors('password')?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="confirm_password" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-4 py-2 border <?php echo errors('confirm_password') ? 'border-red-500' : 'border-blue-200'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php if (errors('confirm_password')): ?>
                        <p class="text-red-500"><?php echo errors('confirm_password')?></p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-700 to-red-700 text-white py-3 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Sign Up</button>
            </form>
            <p class="mt-6 text-center text-gray-600">Already have an account? <a href="/login" class="text-blue-700 hover:underline font-bold">Login</a></p>
        </div>
    </main>
    <?php
        // Use App\Session for flashes
        use App\Session;

        $errorMsg = '';
        if (Session::has('error')) {
            $errorMsg = htmlspecialchars(Session::get('error'));
            Session::unset('error');
        }
    ?>

    <!-- Top-center toast for signup errors -->
    <div id="signup-error-toast" class="fixed left-1/2 top-6 transform -translate-x-1/2 z-50 px-4 pointer-events-none" style="display: <?php echo $errorMsg ? 'block' : 'none'; ?>;">
        <div class="mx-auto w-full max-w-md pointer-events-auto">
            <div class="bg-white shadow-lg rounded-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-gray-900">Error</p>
                    <p class="mt-1 text-sm text-gray-500" id="signup-error-message"><?php echo $errorMsg; ?></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button id="signup-error-close" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-white bg-opacity-95 text-gray-700 text-center py-6 mt-16 border-t-4 border-blue-200">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between">
            <span class="font-bold text-indigo-600 text-lg">POS System</span>
            <span class="text-sm mt-2 md:mt-0">&copy; <?php echo date('Y'); ?> POS System. All rights reserved.</span>
            <a href="mailto:support@example.com" class="text-blue-700 hover:underline text-sm">Contact Support</a>
        </div>
    </footer>
    <script>
        (function(){
            const toast = document.getElementById('signup-error-toast');
            const closeBtn = document.getElementById('signup-error-close');
            if (!toast) return;
            const autoHide = setTimeout(() => { toast.style.display = 'none'; }, 5000);
            if (closeBtn) closeBtn.addEventListener('click', () => { toast.style.display = 'none'; clearTimeout(autoHide); });
        })();
    </script>
</body>
</html>
