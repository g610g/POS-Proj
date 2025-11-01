<?php
// sales.php - Sales listing page
use App\Session;

// Controller may provide $sales as an array of sales records. Each record shape:
// ['id'=>int, 'date'=>'YYYY-MM-DD', 'customer'=>'Name', 'items'=>int, 'total'=>float, 'payment_method'=>'cash'|'card']
if (!isset($sales) || !is_array($sales)) {
    $sales = [
        ['id' => 1001, 'date' => date('Y-m-d'), 'customer' => 'Walk-in', 'items' => 3, 'total' => 750.00, 'payment_method' => 'cash'],
        ['id' => 1000, 'date' => date('Y-m-d', strtotime('-1 day')), 'customer' => 'Jane Doe', 'items' => 2, 'total' => 420.50, 'payment_method' => 'card'],
    ];
}

// Compute totals
$totalSales = 0.0;
$totalItems = 0;
foreach ($sales as $s) {
    $totalSales += (float) $s['total'];
    $totalItems += (int) $s['items'];
}

?>
<div class="py-6">
     <div>
        <canvas id="salesChart"></canvas>
    </div>
    <!-- Chart controls: granularity toggle -->
    <div class="mt-4 mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="inline-flex rounded-lg bg-white shadow p-1">
                <button data-mode="year" class="granularity-btn px-4 py-2 rounded-md text-sm font-medium text-blue-700 bg-blue-50">Year</button>
                <button data-mode="month" class="granularity-btn px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-700">Monthly</button>
                <button data-mode="day" class="granularity-btn px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-700">Daily</button>
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
        <div class="flex items-center space-x-3">
            <form method="GET" action="/sales" class="flex items-center space-x-2">
                <input type="date" name="from" class="px-3 py-2 border rounded-lg" value="<?php echo htmlspecialchars($_GET['from'] ?? ''); ?>">
                <input type="date" name="to" class="px-3 py-2 border rounded-lg" value="<?php echo htmlspecialchars($_GET['to'] ?? ''); ?>">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg">Filter</button>
            </form>
            <a href="#" class="bg-white border border-blue-200 px-4 py-2 rounded-lg">Export CSV</a>
        </div>
    </div>

    <div class="bg-white bg-opacity-95 rounded-2xl shadow p-6 border-2 border-blue-100">
        <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg">
                <div class="text-sm text-gray-500">Total Sales</div>
                <div class="text-xl font-extrabold text-blue-900">₱<?php echo number_format($totalSales,2); ?></div>
            </div>
            <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg">
                <div class="text-sm text-gray-500">Total Items</div>
                <div class="text-xl font-extrabold text-blue-900"><?php echo (int)$totalItems; ?></div>
            </div>
            <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg">
                <div class="text-sm text-gray-500">Transactions</div>
                <div class="text-xl font-extrabold text-blue-900"><?php echo count($sales); ?></div>
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
                <tbody>
                    <?php foreach ($sales as $s): ?>
                        <tr class="hover:bg-blue-50">
                            <td class="px-6 py-3 text-blue-900 font-semibold">#<?php echo htmlspecialchars($s['id']); ?></td>
                            <td class="px-6 py-3 text-gray-600"><?php echo htmlspecialchars($s['date']); ?></td>
                            <td class="px-6 py-3 text-blue-900"><?php echo htmlspecialchars($s['customer']); ?></td>
                            <td class="px-6 py-3 text-right text-blue-900"><?php echo (int)$s['items']; ?></td>
                            <td class="px-6 py-3 text-right text-red-700 font-bold">₱<?php echo number_format((float)$s['total'],2); ?></td>
                            <td class="px-6 py-3 text-center text-sm text-gray-700"><?php echo htmlspecialchars(ucfirst($s['payment_method'] ?? '')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
   
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Sales';
$layoutMode = 'sidebar';
require __DIR__ . '/layout.php';
?>
<script>
// Chart granularity toggle handlers
document.addEventListener('DOMContentLoaded', function(){
    const buttons = document.querySelectorAll('.granularity-btn');
    const selectYear = document.getElementById('select-year');
    const selectMonth = document.getElementById('select-month');
    const selectDay = document.getElementById('select-day');
    const refreshBtn = document.getElementById('chart-refresh');

    function setActive(button) {
        buttons.forEach(b => { b.classList.remove('bg-blue-50'); b.classList.remove('text-blue-700'); b.classList.add('text-gray-600'); });
        button.classList.add('bg-blue-50');
        button.classList.add('text-blue-700');
    }

    function showOptionsFor(mode){
        selectYear.classList.add('hidden');
        selectMonth.classList.add('hidden');
        selectDay.classList.add('hidden');
        if (mode === 'year') selectYear.classList.remove('hidden');
        if (mode === 'month') { selectYear.classList.remove('hidden'); selectMonth.classList.remove('hidden'); }
        if (mode === 'day') selectDay.classList.remove('hidden');
    }

    buttons.forEach(b => {
        b.addEventListener('click', function(){
            const mode = this.getAttribute('data-mode');
            setActive(this);
            showOptionsFor(mode);
            // placeholder: call chart update
            updateChartGranularity(mode, {
                year: selectYear.value || new Date().getFullYear(),
                month: selectMonth.value || (new Date().getMonth()+1),
                day: selectDay.value || new Date().toISOString().slice(0,10)
            });
        });
    });

    refreshBtn.addEventListener('click', function(){
        const active = document.querySelector('.granularity-btn.bg-blue-50');
        const mode = active ? active.getAttribute('data-mode') : 'year';
        updateChartGranularity(mode, {
            year: selectYear.value || new Date().getFullYear(),
            month: selectMonth.value || (new Date().getMonth()+1),
            day: selectDay.value || new Date().toISOString().slice(0,10)
        });
    });

    // Initialize default selection
    const defaultBtn = document.querySelector('.granularity-btn[data-mode="year"]');
    if (defaultBtn) { defaultBtn.click(); }
});

// Placeholder function to integrate with Chart.js later
function updateChartGranularity(mode, opts){
    console.log('updateChartGranularity called', mode, opts);
    // Implement actual data fetch + chart update here.
}
</script>
