<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-200 via-white to-red-200 flex flex-col">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/></svg>
            <span class="text-2xl font-bold text-gray-800">Delete User</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="/src/views/users.php" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-blue-900 transition">Back to Users</a>
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div class="bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 w-full max-w-md mt-10 text-center">
            <h2 class="text-3xl font-extrabold text-center text-red-700 mb-6">Delete User</h2>
            <p class="mb-8 text-gray-700">Are you sure you want to delete this user?</p>
            <form action="/delete/user" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id'] ?? ''); ?>">
                <button type="submit" class="bg-red-700 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-red-900 transition">Delete User</button>
                <a href="/users" class="ml-4 bg-blue-700 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-blue-900 transition">Cancel</a>
            </form>
        </div>
        <!-- Toast / Modal for errors -->
        <?php
            // Use App\Session helper for session/flash management
            // require_once __DIR__ . '/../Session.php';
            use App\Session;

            $errorMsg = '';
            if (Session::has('validation')) {
                $errorMsg = htmlspecialchars(Session::get('validation')['user_id']);
                // clear flash
                Session::unset('validation');
            }
            if (Session::has('error')) {
                $errorMsg = htmlspecialchars(Session::get('error'));
                // clear flash
                Session::unset('error');
            }
        ?>

        <div id="error-toast" aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 z-50" style="display: <?php echo $errorMsg ? 'flex' : 'none'; ?>;">
            <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900">Error</p>
                                <p class="mt-1 text-sm text-gray-500" id="error-toast-message"><?php echo $errorMsg; ?></p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button id="error-toast-close" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
    <script>
        //Show toast/modal on error
        (function() {
            const toast = document.getElementById('error-toast');
            const closeBtn = document.getElementById('error-toast-close');

            if (!toast) return;

            // Auto-hide after 5 seconds
            const autoHide = setTimeout(() => {
                toast.style.display = 'none';
            }, 5000);

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    toast.style.display = 'none';
                    clearTimeout(autoHide);
                });
            }
        })();
    </script>
</body>
</html>
