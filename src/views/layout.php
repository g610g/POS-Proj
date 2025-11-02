<?php
// layout.php - shared layout with sidebar or top-nav
use App\Session;

$currentUser = Session::get('user')['username'] ?? null;
$layoutMode = $layoutMode ?? 'sidebar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle ?? 'POS'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://localhost:5173/src/scripts/utils.js" type="module"></script>
    <style>body{background:linear-gradient(135deg,#e3f2fd 0%,#b3e5fc 60%,#b71c1c 100%);}</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-200 via-white to-red-200 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white bg-opacity-95 border-r border-blue-100 hidden md:flex flex-col h-screen sticky top-0 flex-shrink-0">
        <div class="p-6 border-b border-blue-100">
            <h2 class="text-2xl font-bold text-blue-900">Frozen Meatshop</h2>
            <?php if ($currentUser): ?>
                <p class="text-sm text-gray-600 mt-1">Hello, <span class="font-semibold text-red-700"><?php echo htmlspecialchars($currentUser); ?></span></p>
            <?php endif; ?>
        </div>
        <nav class="flex-1 p-4">
            <a href="/" class="block px-4 py-2 rounded hover:bg-blue-50 text-blue-700 font-medium">Home</a>
            <a href="/shop" class="block px-4 py-2 rounded hover:bg-blue-50 text-blue-700 font-medium">Shop</a>
            <a href="/cart" class="block px-4 py-2 rounded hover:bg-blue-50 text-blue-700 font-medium">Cart</a>
            <a href="/inventory" class="block px-4 py-2 rounded hover:bg-blue-50 text-blue-700 font-medium">Inventory</a>
            <a href="/users" class="block px-4 py-2 rounded hover:bg-blue-50 text-blue-700 font-medium">Users</a>
            <a href="/dashboard" class="block px-4 py-2 rounded hover:bg-blue-50 text-blue-700 font-medium">Dashboard</a>
        </nav>
        <div class="p-4 border-t border-blue-100">
            <?php if ($currentUser): ?>
                <form action="/logout" method="POST"><button class="w-full text-left text-sm text-red-700 font-bold">Logout</button></form>
            <?php else: ?>
                <a href="/login" class="w-full inline-block text-center bg-gradient-to-r from-blue-700 to-red-700 text-white px-4 py-2 rounded">Login</a>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Mobile top nav -->
    <header class="md:hidden w-full bg-white bg-opacity-95 border-b border-blue-100 flex items-center justify-between p-4">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
            <span class="text-lg font-bold text-blue-900"><?php echo htmlspecialchars($pageTitle ?? 'POS'); ?></span>
        </div>
        <div>
            <button id="mobile-menu-toggle" class="text-blue-700 font-bold">Menu</button>
        </div>
    </header>

    <!-- Main content area -->
    <div class="flex-1 min-h-screen flex flex-col">
        <div id="mobile-sidebar" class="md:hidden hidden bg-white bg-opacity-95 border-b border-blue-100 p-4">
            <a href="/" class="block px-4 py-2">Home</a>
            <a href="/shop" class="block px-4 py-2">Shop</a>
            <a href="/cart" class="block px-4 py-2">Cart</a>
            <a href="/inventory" class="block px-4 py-2">Inventory</a>
            <a href="/users" class="block px-4 py-2">Users</a>
            <a href="/dashboard" class="block px-4 py-2">Dashboard</a>
        </div>

    <main class="p-8 flex-1 overflow-auto">
            <?php if (!empty($pageTitle)): ?>
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-extrabold text-blue-900"><?php echo htmlspecialchars($pageTitle); ?></h1>
                </div>
            <?php endif; ?>

            <div class="max-w-7xl mx-auto">
                <?php echo $content ?? ''; ?>
            </div>
        </main>
         <?php
        // Use App\Session for flashes
        $errorMsg = '';
if (Session::has('error')) {
    $errorMsg = htmlspecialchars(Session::get('error'));
    Session::unset('error');
}
?>
        <!-- Top-center toast for signup errors -->
    <div id="signup-error-toast" class="fixed left-1/2 top-6 transform -translate-x-1/2 z-50 px-4 pointer-events-none w-[400px]" style="display: <?php echo $errorMsg ? 'block' : 'none'; ?>;">
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
    </div>

    <script>
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function(){
            const el = document.getElementById('mobile-sidebar');
            if (!el) return; el.classList.toggle('hidden');
        });
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
