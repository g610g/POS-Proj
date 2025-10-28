<?php
// shop.php - Customer-facing shop page
require_once __DIR__ . '/../Session.php';
use App\Session;
Session::start();
$currentUser = Session::get('user')['username'] ?? null;

// compute cart count from session
$cartCount = 0;
$cart = Session::get('cart');
if ($cart) {
    var_dump($cart);
    $cartCount = count($cart);
}

// If controller provides $products, use it. Otherwise fall back to example products.
if (!isset($products) || !is_array($products)) {
    $products = [
        ['product_id' => 1, 'product_name' => 'Frozen Beef - 1kg', 'price' => '₱250.00', 'stock' => 12, 'image' => '/assets/f42335844178bb1497255c0badc057c6.jpg'],
        ['product_id' => 2, 'product_name' => 'Frozen Pork - 1kg', 'price' => '₱220.00', 'stock' => 8, 'image' => '/assets/Olaf Digital Download - Used for any program, Cricuit, PhotoShop, Google, Microsoft - PNG,.jpg'],
        ['product_id' => 3, 'product_name' => 'Chicken Thigh - 1kg', 'price' => '₱180.00', 'stock' => 20, 'image' => '/assets/f42335844178bb1497255c0badc057c6.jpg'],
        ['product_id' => 4, 'product_name' => 'Ground Beef - 500g', 'price' => '₱140.00', 'stock' => 7, 'image' => '/assets/f42335844178bb1497255c0badc057c6.jpg'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Shop - Frozen Meatshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-200 via-white to-red-200 flex flex-col">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-10 h-10 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
            <span class="text-2xl font-extrabold text-blue-900 tracking-wide">Frozen Meatshop Shop</span>
        </div>
        <div class="flex items-center space-x-6">
            <a href="/" class="text-blue-700 font-medium">Home</a>
            <a href="/cart" class="relative inline-flex items-center text-blue-700 font-medium">
                Cart
                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    <?php echo $cartCount; ?>
                </span>
            </a>
            <?php if ($currentUser): ?>
                <span class="text-blue-900">Welcome, <span class="font-bold text-red-700"><?php echo htmlspecialchars($currentUser); ?></span></span>
            <?php else: ?>
                <a href="/login" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-4 py-2 rounded-lg font-bold shadow">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="flex-1 p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-extrabold text-blue-900">Shop</h1>
                <p class="text-gray-700">Browse and add items to your cart</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $p): ?>
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col">
                    <div class="h-40 w-full rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center mb-4">
                        <?php if (!empty($p['image'])): ?>
                            <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['product_name']); ?>" class="h-full object-contain">
                        <?php else: ?>
                            <div class="text-gray-400">No image</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-1"><?php echo htmlspecialchars($p['product_name']); ?></h3>
                    <p class="text-red-700 font-bold mb-2"><?php echo htmlspecialchars($p['price']); ?></p>
                    <p class="text-sm text-gray-600 mb-4">Stock: <?php echo htmlspecialchars($p['stock']); ?></p>

                    <form action="/cart/add" method="POST" class="mt-auto">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($p['product_id']); ?>">
                        <div class="flex items-center space-x-2 mb-3">
                            <label class="text-sm text-gray-700">Qty</label>
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo max(1, (int)$p['stock']); ?>" class="w-20 px-3 py-1 border border-blue-200 rounded-lg focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-700 to-red-700 text-white px-4 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Add to cart</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="bg-white bg-opacity-95 text-gray-700 text-center py-6 mt-12 border-t-4 border-blue-200">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between">
            <span class="font-bold text-blue-900 text-lg">Frozen Meatshop</span>
            <span class="text-sm mt-2 md:mt-0">&copy; <?php echo date('Y'); ?> Frozen Meatshop. All rights reserved.</span>
            <a href="mailto:support@example.com" class="text-blue-700 hover:underline text-sm">Contact Support</a>
        </div>
    </footer>
</body>
</html>
