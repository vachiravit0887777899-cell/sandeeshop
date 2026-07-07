<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แดชบอร์ดแอดมิน</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- การ์ดสรุปยอด -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 mb-1">ยอดขายรวมทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">฿{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 mb-1">ยอดขายวันนี้</p>
                    <p class="text-2xl font-bold text-green-600">฿{{ number_format($todayRevenue, 2) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 mb-1">ยอดเติมเงินรวม</p>
                    <p class="text-2xl font-bold text-indigo-600">฿{{ number_format($totalDeposits, 2) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 mb-1">จำนวนผู้ใช้ทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }} คน</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 mb-1">กล่องสุ่มทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBoxes) }} กล่อง</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 mb-1">จำนวนครั้งที่เปิดกล่องทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOpenings) }} ครั้ง</p>
                </div>
            </div>

            <!-- กราฟยอดขาย 7 วัน -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">ยอดขาย 7 วันล่าสุด</h3>
                <canvas id="salesChart" height="80"></canvas>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- กล่องขายดี -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">กล่องสุ่มยอดนิยม (Top 5)</h3>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-sm text-gray-500 border-b">
                                <th class="p-2">ชื่อกล่อง</th>
                                <th class="p-2 text-right">ถูกเปิด (ครั้ง)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topBoxes as $box)
                                <tr class="border-b">
                                    <td class="p-2">{{ $box->name }}</td>
                                    <td class="p-2 text-right font-medium">{{ $box->openings_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="p-4 text-center text-gray-500">ยังไม่มีข้อมูล</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ผู้ใช้ล่าสุด -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">สมาชิกใหม่ล่าสุด</h3>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-sm text-gray-500 border-b">
                                <th class="p-2">ชื่อ</th>
                                <th class="p-2">อีเมล</th>
                                <th class="p-2 text-right">สมัครเมื่อ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $user)
                                <tr class="border-b">
                                    <td class="p-2">{{ $user->name }}</td>
                                    <td class="p-2 text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="p-2 text-right text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-4 text-center text-gray-500">ยังไม่มีข้อมูล</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ธุรกรรมล่าสุด -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">ธุรกรรมล่าสุด</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-sm text-gray-500 border-b">
                            <th class="p-2">ผู้ใช้</th>
                            <th class="p-2">ประเภท</th>
                            <th class="p-2">รายละเอียด</th>
                            <th class="p-2 text-right">จำนวน</th>
                            <th class="p-2 text-right">เวลา</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr class="border-b">
                                <td class="p-2">{{ $transaction->user->name ?? '-' }}</td>
                                <td class="p-2">
                                    <span @class([
                                        'px-2 py-1 rounded text-xs',
                                        'bg-green-100 text-green-700' => $transaction->type === 'deposit',
                                        'bg-red-100 text-red-700' => $transaction->type === 'purchase',
                                        'bg-blue-100 text-blue-700' => $transaction->type === 'refund',
                                    ])>
                                        {{ match($transaction->type) {
                                            'deposit' => 'เติมเงิน',
                                            'purchase' => 'ซื้อกล่อง',
                                            'refund' => 'คืนเงิน',
                                            default => $transaction->type,
                                        } }}
                                    </span>
                                </td>
                                <td class="p-2 text-sm text-gray-600">{{ $transaction->description }}</td>
                                <td class="p-2 text-right font-medium">฿{{ number_format($transaction->amount, 2) }}</td>
                                <td class="p-2 text-right text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-4 text-center text-gray-500">ยังไม่มีธุรกรรม</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ใช้ Chart.js สำหรับกราฟยอดขาย -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: @json($chartData),
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>