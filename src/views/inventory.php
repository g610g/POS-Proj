<?php
// inventory.php - Product Management Page (UI only, no backend yet)
require_once __DIR__ . '/../Session.php';
use App\Session;
Session::start();
$currentUser = Session::get('user')['username'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 60%, #b71c1c 100%);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-200 via-white to-red-200 flex flex-col">
    <nav class="bg-white bg-opacity-95 shadow-lg py-4 px-8 flex items-center justify-between border-b-4 border-blue-200">
        <div class="flex items-center space-x-3">
            <svg class="w-10 h-10 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/></svg>
            <span class="text-2xl font-extrabold text-blue-900 tracking-wide">Frozen Meatshop Inventory</span>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-blue-900 text-lg font-medium">
                <?php if ($currentUser): ?>
                    Welcome, <span class="font-bold text-red-700"><?php echo htmlspecialchars($currentUser); ?></span>
                <?php else: ?>
                    Welcome, Guest
                <?php endif; ?>
            </span>
            <form action="/logout" method="POST">
                <button type="submit" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-4 py-2 rounded-lg font-bold shadow hover:from-blue-900 hover:to-red-800 transition">Logout</button>
            </form>
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center p-8">
        <div class="w-full max-w-4xl bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 mt-10 border-2 border-blue-200">
            <h2 class="text-2xl font-extrabold text-blue-900 mb-6 tracking-wide">Product List</h2>
            <div class="flex justify-end mb-4">
                <button class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:from-blue-900 hover:to-red-800 transition-all duration-200" onclick="openAddModal()">Add Product</button>
            </div>
            <div class="overflow-x-auto rounded-2xl shadow-lg">
                <table class="min-w-full bg-white rounded-2xl overflow-hidden border-2 border-blue-100">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-700 to-red-700 text-white">
                            <th class="px-8 py-4 text-left text-sm font-bold uppercase tracking-wider">Product Name</th>
                            <th class="px-8 py-4 text-left text-sm font-bold uppercase tracking-wider">Price</th>
                            <th class="px-8 py-4 text-left text-sm font-bold uppercase tracking-wider">Stock</th>
                            <th class="px-8 py-4 text-center text-sm font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-blue-50 transition-all duration-150">
                            <td class="px-8 py-4 whitespace-nowrap text-blue-900 font-semibold border-b border-blue-100"> <?php echo $product['product_name'] ?></td>
                            <td class="px-8 py-4 whitespace-nowrap text-red-700 font-bold border-b border-blue-100"> <?php echo $product['price'] ?></td>
                            <td class="px-8 py-4 whitespace-nowrap text-blue-700 border-b border-blue-100"> <?php echo $product['stock'] ?></td>
                            <td class="px-8 py-4 whitespace-nowrap text-center border-b border-blue-100">
                                <button 
                                    class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-5 py-2 rounded-xl font-bold shadow hover:from-blue-900 hover:to-red-800 transition-all duration-150 mr-2" 
                                    data-id="<?php echo htmlspecialchars($product['product_id'] ?? ''); ?>"
                                    data-name="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>"
                                    data-price="<?php 
                                    // Remove "₱", "PHP", commas, and any spaces
                                    $clean = preg_replace('/[₱,]|PHP/i', '', $product['price']);

                                    // Trim and convert to float
                                    $value = (float) trim($clean);
                                    echo htmlspecialchars($value ?? 0.0); 
                                    ?>"
                                    data-stock="<?php echo htmlspecialchars($product['stock'] ?? ''); ?>"
                                    onclick="handleEditButtonClick(this)">
                                    Edit
                                </button>
                                <button class="bg-gradient-to-r from-red-700 to-blue-700 text-white px-5 py-2 rounded-xl font-bold shadow hover:from-red-900 hover:to-blue-800 transition-all duration-150" 
                                    data-id="<?php echo htmlspecialchars($product['product_id'] ?? ''); ?>"
                                    data-name="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>"
                                    onclick="handleDeleteButtonClick(this)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Add Product Modal -->
        <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md border-2 border-blue-200">
                <h3 class="text-xl font-bold mb-4">Add Product</h3>
                <form action="/add-product" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Product Name</label>
                        <input name="product_name" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter product name">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Price</label>
                        <input name="price" type="number" class="w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter price">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Stock</label>
                        <input name="stock" type="number" class="w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter stock">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Product Image</label>
                        <input name="product_image" type="file" accept="image/*" class="w-full" />
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-300 px-4 py-2 rounded-lg" onclick="closeAddModal()">Cancel</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-green-700 transition">Add</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Edit Product Modal -->
        <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md border-2 border-blue-200">
                <h3 class="text-xl font-bold mb-4">Edit Product</h3>
                <form action="/edit-product" method="POST">
                    <input type="hidden" name="edit_product_id" id="edit_product_id">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Product Name</label>
                        <input type="text" name="edit_product_name" id="edit_product_name" class="w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Price</label>
                        <input type="number" name="edit_price" id="edit_price" class="w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Stock</label>
                        <input type="number" name="edit_stock" id="edit_stock" class="w-full px-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-300 px-4 py-2 rounded-lg" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-yellow-600 transition">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Delete Product Modal -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center border-2 border-blue-200">
                <h3 class="text-xl font-bold mb-4">Delete Product</h3>
                <form action="/delete-product" method="POST">
                    <input type="hidden" id="delete_product_id" name="delete_product_id">
                    <p class="mb-6">Are you sure you want to delete <span class="font-semibold" id="delete_product_name">this product</span>?</p>
                    <div class="flex justify-center space-x-2">
                        <button type="button" class="bg-gray-300 px-4 py-2 rounded-lg" onclick="closeDeleteModal()">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-red-700 transition" onclick="confirmDeleteProduct()">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer class="text-center text-blue-900 py-6 text-sm font-semibold">
        &copy; 2025 Frozen Meatshop POS. All rights reserved.
    </footer>
    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
        function handleEditButtonClick(btn) {
            const product = {
                id: btn.getAttribute('data-id'),
                product_name: btn.getAttribute('data-name'),
                price: btn.getAttribute('data-price'),
                stock: btn.getAttribute('data-stock')
            };
            openEditModal(product);
        }
        function openEditModal(product) {
            console.log("Opening edit modal for product:", product);
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('edit_product_id').value = product.id;
            document.getElementById('edit_product_name').value = product.product_name;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock;
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        function handleDeleteButtonClick(btn) {
            const productId = btn.getAttribute('data-id');
            const productName = btn.getAttribute('data-name');
            openDeleteModal(productId, productName);
        }
        function openDeleteModal(productId, productName) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('delete_product_id').value = productId;
            document.getElementById('delete_product_name').textContent = productName || 'this product';
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        function confirmDeleteProduct() {
            // Placeholder for delete logic
            closeDeleteModal();
        }
    </script>
</body>
</html>
