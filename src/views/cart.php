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
                    <button id="open-checkout" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-lg font-bold">Checkout</button>
                </div>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl p-6 mx-4">
                <div class="flex items-start justify-between">
                    <h3 class="text-xl font-bold text-blue-900">Confirm Checkout</h3>
                    <button id="close-checkout" class="text-gray-500 hover:text-gray-700">Close</button>
                </div>
                <div class="mt-4">
                    <div class="mb-4 text-sm text-gray-700">Please review your order before proceeding. You will be charged once you confirm.</div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <?php foreach ($items as $it): ?>
                            <div class="flex justify-between py-2 border-b">
                                <div>
                                    <div class="font-semibold text-blue-900"><?php echo htmlspecialchars($it['product_name'] ?? 'Item'); ?></div>
                                    <div class="text-sm text-gray-600">Qty: <?php echo (int)($it['quantity'] ?? 1); ?></div>
                                </div>
                                <div class="text-right text-gray-800"><?php echo htmlspecialchars(encodePrice($it['total_amount']));?></div>
                            </div>
                        <?php endforeach; ?>
                        <div class="flex justify-between pt-3">
                            <div class="font-bold">Total</div>
                            <div class="font-extrabold text-blue-900"><?php echo encodePrice($total); ?></div>
                        </div>
                    </div>

                    <form id="checkout-form" action="/cart/checkout" method="POST" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label class="cursor-pointer p-3 border rounded-lg flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="card" checked class="h-4 w-4">
                                    <span class="font-medium">Card (Credit/Debit)</span>
                                </label>
                                <label class="cursor-pointer p-3 border rounded-lg flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="cash" class="h-4 w-4">
                                    <span class="font-medium">Cash on Delivery</span>
                                </label>
                            </div>
                        </div>

                        <div id="card-fields" class="space-y-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card Number</label>
                                <input required type="text" name="card_number" class="mt-1 block w-full px-3 py-2 border border-blue-200 rounded-lg" placeholder="4242 4242 4242 4242">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Expiry</label>
                                    <input required type="text" name="card_expiry" class="mt-1 block w-full px-3 py-2 border border-blue-200 rounded-lg" placeholder="MM/YY">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">CVC</label>
                                    <input required type="text" name="card_cvc" class="mt-1 block w-full px-3 py-2 border border-blue-200 rounded-lg" placeholder="123">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-5 py-2 rounded-lg font-bold">Confirm & Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <script>
        const checkoutButton = document.getElementById('open-checkout');
        checkoutButton.addEventListener('click', () => {
            document.getElementById('checkout-modal').classList.remove('hidden');
            document.getElementById('checkout-modal').classList.add('flex');
        });
    </script>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Your Cart';
require __DIR__ . '/layout.php';
