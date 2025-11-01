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
    $cartCount = count($cart);
}

// If controller provides $products, use it. Otherwise fall back to example products.
// if (!isset($products) || !is_array($products)) {
//     $products = [
//         ['product_id' => 1, 'product_name' => 'Frozen Beef - 1kg', 'price' => '₱250.00', 'stock' => 12, 'image' => '/assets/f42335844178bb1497255c0badc057c6.jpg'],
//         ['product_id' => 2, 'product_name' => 'Frozen Pork - 1kg', 'price' => '₱220.00', 'stock' => 8, 'image' => '/assets/Olaf Digital Download - Used for any program, Cricuit, PhotoShop, Google, Microsoft - PNG,.jpg'],
//         ['product_id' => 3, 'product_name' => 'Chicken Thigh - 1kg', 'price' => '₱180.00', 'stock' => 20, 'image' => '/assets/f42335844178bb1497255c0badc057c6.jpg'],
//         ['product_id' => 4, 'product_name' => 'Ground Beef - 500g', 'price' => '₱140.00', 'stock' => 7, 'image' => '/assets/f42335844178bb1497255c0badc057c6.jpg'],
//     ];
// }
ob_start();
?>
    <div class="py-6">
        <div class="mb-3 text-gray-700">Browse and add items to your cart</div>

        <!-- Floating cart button -->
        <a href="/cart" class="fixed right-6 top-24 z-40 bg-white shadow-lg rounded-full p-3 flex items-center space-x-3 hover:scale-105 transform transition" aria-label="Open cart">
            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
            <div class="relative">
                <span class="text-sm font-medium text-blue-900">Cart</span>
                <span class="absolute -top-3 -right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"><?php echo (int)$cartCount; ?></span>
            </div>
        </a>

        <?php if (!isset($products) || count($products) === 0): ?>
            <div class="w-full py-12">
                <div class="max-w-3xl mx-auto bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-10 text-center">
                    <svg class="mx-auto mb-6 w-20 h-20 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M8 3v4M16 3v4"/></svg>
                    <h2 class="text-2xl font-extrabold text-blue-900 mb-2">No products available</h2>
                    <p class="text-gray-600 mb-6">We're sorry — we don't have any products in the shop right now. Check back later or contact support if you need immediate assistance.</p>
                    <div class="flex items-center justify-center space-x-3">
                        <a href="/shop" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-700 to-red-700 text-white rounded-lg font-semibold">Refresh</a>
                        <a href="mailto:support@example.com" class="inline-flex items-center px-4 py-2 border border-blue-200 text-blue-700 rounded-lg">Contact Support</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $p): ?>
                    <?php if ($p['stock'] > 0):?>
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
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php
$content = ob_get_clean();
$pageTitle = 'Shop';
require __DIR__ . '/layout.php';
