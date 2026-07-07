<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">กระเป๋าเงินของฉัน</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- การ์ดยอดเงิน -->
           <div class="rounded-xl p-8 text-white mb-6" style="background: linear-gradient(135deg, var(--void), var(--vault) 60%, var(--violet));">
                <p class="text-sm opacity-80 mb-1">ยอดเงินคงเหลือ</p>
               <p class="font-display text-4xl font-bold mb-4">฿<span class="font-mono-data">{{ number_format($user->balance, 2) }}</span></p>
                <a href="{{ route('wallet.topup.form') }}" class="inline-block px-6 py-2 bg-[var(--gold)] text-[var(--void)] rounded-lg font-semibold hover:brightness-110 transition">
                    + เติมเงิน
                </a>
            </div>

            <!-- ประวัติธุรกรรม -->
            <div class="card-foil bg-white rounded-xl shadow-sm border border-[var(--vault-light)]/10 p-6">
    <h3 class="font-display font-semibold text-gray-800 mb-4">ประวัติธุรกรรม</h3>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-sm text-gray-500">
                            <th class="p-2">วันที่</th>
                            <th class="p-2">ประเภท</th>
                            <th class="p-2">รายละเอียด</th>
                            <th class="p-2 text-right">จำนวน</th>
                            <th class="p-2 text-right">ยอดคงเหลือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b">
                                <td class="p-2 text-sm">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-2">
                                    <span @class([
                                        'px-2 py-1 rounded text-xs',
                                        'bg-green-100 text-green-700' => $transaction->type === 'deposit',
                                        'bg-red-100 text-red-700' => $transaction->type === 'purchase',
                                        'bg-blue-100 text-blue-700' => $transaction->type === 'refund',
                                        'bg-gray-100 text-gray-700' => $transaction->type === 'withdraw',
                                    ])>
                                        {{ match($transaction->type) {
                                            'deposit' => 'เติมเงิน',
                                            'purchase' => 'ซื้อกล่อง',
                                            'refund' => 'คืนเงิน',
                                            'withdraw' => 'ถอนเงิน',
                                        } }}
                                    </span>
                                </td>
                                <td class="p-2 text-right font-mono-data font-medium {{ in_array($transaction->type, ['deposit', 'refund']) ? 'text-green-600' : 'text-red-600' }}">
    {{ in_array($transaction->type, ['deposit', 'refund']) ? '+' : '-' }}฿{{ number_format($transaction->amount, 2) }}
</td>
<td class="p-2 text-right font-mono-data text-sm text-gray-500">฿{{ number_format($transaction->balance_after, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">ยังไม่มีประวัติธุรกรรม</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>