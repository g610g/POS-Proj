<?php
// sales.php - Sales listing page
use App\Session;

?>
<div class="py-6">
     <div>
        <canvas id="salesChart"></canvas>
    </div>
    <!-- Chart controls: granularity toggle -->
    <div class="mt-4 mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="inline-flex rounded-lg bg-white shadow p-1">
                <button data-mode="yearly" class="granularity-btn px-4 py-2 rounded-md text-sm font-medium text-blue-700 bg-blue-50">Year</button>
                <button data-mode="monthly" class="granularity-btn px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-700">Monthly</button>
                <button data-mode="daily" class="granularity-btn px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-700">Daily</button>
            </div>

            <div id="granularity-options" class="flex items-center space-x-2">
                <!-- Year selector -->
                <select id="select-year" class="px-3 py-2 border rounded-lg hidden">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                    <?php endfor; ?>
                </select>

                <!-- Month selector -->
                <select id="select-month" class="px-3 py-2 border rounded-lg hidden">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>"><?php echo date('F', mktime(0,0,0,$m,1)); ?></option>
                    <?php endfor; ?>
                </select>

                <!-- Day selector (date input) -->
                <input id="select-day" type="date" class="px-3 py-2 border rounded-lg hidden" />
            </div>
        </div>

        <div>
            <button id="chart-refresh" class="bg-gradient-to-r from-blue-700 to-red-700 text-white px-4 py-2 rounded-lg">Refresh Chart</button>
        </div>
    </div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-blue-900">Sales</h2>
            <p class="text-sm text-gray-600">Sales records and summary</p>
        </div>
       
    </div>

    <div class="bg-white bg-opacity-95 rounded-2xl shadow p-6 border-2 border-blue-100">
        <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg">
                <div class="text-sm text-gray-500">Total Sales</div>
                <div class="text-xl font-extrabold text-blue-900">₱<span id="total-sales"><?php echo number_format($totalSales,2); ?></span></div>
            </div>
            <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg">
                <div class="text-sm text-gray-500">Total Items</div>
                <div class="text-xl font-extrabold text-blue-900"><span id="total-items"><?php echo (int)$totalItems; ?></span></div>
            </div>
            <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg">
                <div class="text-sm text-gray-500">Transactions</div>
                <div class="text-xl font-extrabold text-blue-900"><span id="transactions-count"><?php echo count($sales); ?></span></div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-700 to-red-700 text-white">
                        <th class="px-6 py-3 text-left text-sm font-bold">Invoice</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Customer</th>
                        <th class="px-6 py-3 text-right text-sm font-bold">Items</th>
                        <th class="px-6 py-3 text-right text-sm font-bold">Total</th>
                        <th class="px-6 py-3 text-center text-sm font-bold">Payment</th>
                    </tr>
                </thead>
                <tbody id="sales-table-body">
                    <?php foreach ($sales as $s): ?>
                        
                        <tr class="hover:bg-blue-50">
                            <td class="px-6 py-3 text-blue-900 font-semibold">#<?php echo htmlspecialchars($s['order_id']); ?></td>
                            <td class="px-6 py-3 text-gray-600"><?php echo htmlspecialchars($s['order_date']); ?></td>
                            <td class="px-6 py-3 text-blue-900"><?php echo htmlspecialchars($s['customer_name'] ?? "Gio Gonzales"); ?></td>
                            <td class="px-6 py-3 text-right text-blue-900"><?php echo (int)$s['total_amount']; ?></td>
                            <td class="px-6 py-3 text-right text-red-700 font-bold">₱<?php echo number_format((float)$s['total_amount'],2); ?></td>
                            <td class="px-6 py-3 text-center text-sm text-gray-700"><?php echo htmlspecialchars(ucfirst($s['payment_method' ] ?? 'Card')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
   
</div>
    <script>
    // Embed PHP sales into JS and provide table update functions
    const SALES_DATA = <?php echo $sales;?>;
    console.log(SALES_DATA)
    function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }


    function renderSalesTable(rows){
        const tbody = document.getElementById('sales-table-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        let total = 0; let items = 0;
        rows.forEach(r => {
            const tr = document.createElement('tr'); tr.className = 'hover:bg-blue-50';
            tr.innerHTML = `
                <td class="px-6 py-3 text-blue-900 font-semibold">#${escapeHtml(String(r.id))}</td>
                <td class="px-6 py-3 text-gray-600">${escapeHtml(String(r.date))}</td>
                <td class="px-6 py-3 text-blue-900">${escapeHtml(String(r.customer || ''))}</td>
                <td class="px-6 py-3 text-right text-blue-900">${Number(r.items || 0)}</td>
                <td class="px-6 py-3 text-right text-red-700 font-bold">₱${Number(r.total || 0).toFixed(2)}</td>
                <td class="px-6 py-3 text-center text-sm text-gray-700">${escapeHtml(String(r.payment_method || ''))}</td>
            `;
            tbody.appendChild(tr);
            total += Number(r.total || 0);
            items += Number(r.items || 0);
        });
        document.getElementById('total-sales').textContent = Number(total).toFixed(2);
        document.getElementById('total-items').textContent = Number(items);
        document.getElementById('transactions-count').textContent = rows.length;
    }

    document.addEventListener('DOMContentLoaded', function(){
        const buttons = document.querySelectorAll('.granularity-btn');
        const selectYear = document.getElementById('select-year');
        const selectMonth = document.getElementById('select-month');
        const selectDay = document.getElementById('select-day');
        const refreshBtn = document.getElementById('chart-refresh');

        function setActive(button){ buttons.forEach(b => { b.classList.remove('bg-blue-50'); b.classList.remove('text-blue-700'); b.classList.add('text-gray-600'); }); button.classList.add('bg-blue-50'); button.classList.add('text-blue-700'); }
        function showOptionsFor(mode){ selectYear.classList.add('hidden'); selectMonth.classList.add('hidden'); selectDay.classList.add('hidden'); if (mode==='yearly') selectYear.classList.remove('hidden'); if (mode==='monthly'){ selectYear.classList.remove('hidden'); selectMonth.classList.remove('hidden'); } if (mode==='daily') selectDay.classList.remove('hidden'); }
        function update(mode){ const opts = { year: selectYear.value || new Date().getFullYear(), month: selectMonth.value || (new Date().getMonth()+1), day: selectDay.value || new Date().toISOString().slice(0,10) }; const rows = aggregateSales(mode, opts); renderSalesTable(rows); }

        buttons.forEach(b => b.addEventListener('click', function(){ const mode = this.getAttribute('data-mode'); setActive(this); showOptionsFor(mode); update(mode); }));
        refreshBtn.addEventListener('click', function(){ const active = document.querySelector('.granularity-btn.bg-blue-50'); const mode = active ? active.getAttribute('data-mode') : 'yearly'; update(mode); });
        const defaultBtn = document.querySelector('.granularity-btn[data-mode="yearly"]'); if (defaultBtn) defaultBtn.click();
    });
    </script>

<?php
    $content = ob_get_clean();
    $pageTitle = 'Sales';
    $layoutMode = 'sidebar';
    require __DIR__ . '/layout.php';
?>
