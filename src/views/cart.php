<?php

use App\Session;

$cart = Session::get('cart') ?? [];
$items = [];
if (is_array($cart)) {
    // Case: associative by id or list of items
    foreach ($cart as $k => $v) {
        if (is_array($v)) {
            $items[] = $v;
        }
    }
}

// build HTML content
?>
<div class="bg-white bg-opacity-95 rounded-2xl shadow-xl p-6">
    <h2 class="text-xl font-bold text-blue-900 mb-4">Your Cart</h2>
    <?php if (empty($items)): ?>
        <p class="text-gray-700">Your cart is empty. <a href="/shop" class="text-blue-700 font-bold">Continue shopping</a></p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="text-left text-sm text-gray-600 uppercase tracking-wider">
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Subtotal</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it): ?>
                        <?php?>
                        <tr class="border-t">
                            <td class="px-4 py-3 font-semibold text-blue-900"><?php echo htmlspecialchars($it['product_name'] ?? 'Item'); ?></td>
                            <td class="px-4 py-3 text-red-700"><?php echo isset($it['price']) ? htmlspecialchars(\encodePrice($it['price'])) : '₱0.00'; ?></td>
                            <td class="px-4 py-3"><?php echo $it['quantity']; ?></td>
                            <td class="px-4 py-3 text-gray-800">₱<?php echo number_format($it['total_amount'],2); ?></td>
                            <td class="px-4 py-3">
                                <form action="/cart/remove" method="POST" class="inline">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($it['product_id'] ?? ''); ?>">
                                    <button type="submit" class="text-red-700 font-bold">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex items-center justify-end">
            <div class="text-right">
                <div class="text-sm text-gray-600">Total</div>
                <div class="text-2xl font-extrabold text-blue-900">₱<?php echo number_format($total,2); ?></div>
                <div class="mt-3">
                    <a href="/checkout" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-lg font-bold">Checkout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Your Cart';
require __DIR__ . '/layout.php';
