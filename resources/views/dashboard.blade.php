<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            สวัสดี, {{ $user->name }} 👋
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- การ์ดยอดเงิน + ทางลัด -->
            <div class="rounded-xl p-8 text-white" style="background: linear-gradient(135deg, var(--void), var(--vault) 60%, var(--violet));">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm opacity-80 mb-1">ยอดเงินคงเหลือ</p>
                       <p class="font-display text-4xl font-bold">฿<span class="font-mono-data">{{ number_format($user->balance, 2) }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('wallet.topup.form') }}" class="px-5 py-2 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100">
                            + เติมเงิน
                        </a>
                        <a href="{{ route('shop.index') }}" class="px-5 py-2 bg-[var(--gold)] text-[var(--void)] rounded-lg font-semibold hover:brightness-110 transition">
                            🎁 เปิดกล่องสุ่ม
                        </a>
                    </div>
                </div>
            </div>

            <!-- การ์ดสถิติ -->
            <div class="card-foil bg-white rounded-xl shadow-sm border border-[var(--vault-light)]/10 p-5">
    <p class="text-sm text-[var(--ink-dim)] mb-1">ไอเทมในคลัง</p>
    <p class="font-display text-2xl font-bold text-gray-900">{{ $inventoryCount }} <span class="text-base font-normal">ชิ้น</span></p>
</div>
<div class="card-foil bg-white rounded-xl shadow-sm border border-[var(--vault-light)]/10 p-5">
    <p class="text-sm text-[var(--ink-dim)] mb-1">เปิดกล่องแล้ว</p>
    <p class="font-display text-2xl font-bold text-gray-900">{{ $totalOpenings }} <span class="text-base font-normal">ครั้ง</span></p>
</div>
<div class="card-foil bg-white rounded-xl shadow-sm border border-[var(--vault-light)]/10 p-5">
    <p class="text-sm text-[var(--ink-dim)] mb-1">ของหายาก (Epic+)</p>
    <p class="font-display text-2xl font-bold text-[var(--violet)]">{{ $rareItemsCount }} <span class="text-base font-normal">ชิ้น</span></p>
</div>
<div class="card-foil bg-white rounded-xl shadow-sm border border-[var(--vault-light)]/10 p-5">
    <p class="text-sm text-[var(--ink-dim)] mb-1">ใช้จ่ายรวม</p>
    <p class="font-display text-2xl font-bold text-gray-900">฿<span class="font-mono-data">{{ number_format($totalSpent, 2) }}</span></p>
</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- ไอเทมล่าสุดในคลัง -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-800">ไอเทมล่าสุดของคุณ</h3>
                        <a href="{{ route('inventory.index') }}" class="text-sm text-indigo-600">ดูทั้งหมด &rarr;</a>
                    </div>

                    @if ($recentItems->isEmpty())
                        <p class="text-gray-500 text-center py-8">ยังไม่มีไอเทมในคลัง ลองเปิดกล่องสุ่มดูสิ!</p>
                    @else
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($recentItems as $inventory)
                                <div class="text-center">
                                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-1">
                                        @if ($inventory->boxItem->image)
                                            <img src="{{ image_url($inventory->boxItem->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">ไม่มีรูป</div>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-700 truncate">{{ $inventory->boxItem->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ประวัติเปิดกล่องล่าสุด -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-800">ประวัติเปิดกล่องล่าสุด</h3>
                        <a href="{{ route('wallet.index') }}" class="text-sm text-indigo-600">ดูประวัติธุรกรรม &rarr;</a>
                    </div>

                    @if ($recentOpenings->isEmpty())
                        <p class="text-gray-500 text-center py-8">ยังไม่มีประวัติการเปิดกล่อง</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentOpenings as $opening)
                                <div class="flex items-center justify-between border-b pb-3 last:border-b-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded overflow-hidden shrink-0">
                                            @if ($opening->boxItem->image)
                                                <img src="{{ Storage::url($opening->boxItem->image) }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $opening->boxItem->name }}</p>
                                            <p class="text-xs text-gray-500">จาก {{ $opening->box->name }}</p>
                                        </div>
                                    </div>
                                    <span @class([
                                        'px-2 py-1 rounded text-xs shrink-0',
                                        'bg-gray-100 text-gray-700' => $opening->boxItem->rarity === 'common',
                                        'bg-blue-100 text-blue-700' => $opening->boxItem->rarity === 'rare',
                                        'bg-purple-100 text-purple-700' => $opening->boxItem->rarity === 'epic',
                                        'bg-yellow-100 text-yellow-700' => $opening->boxItem->rarity === 'legendary',
                                    ])>
                                        {{ ucfirst($opening->boxItem->rarity) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>