<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">คลังไอเทมของฉัน</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @forelse ($inventories as $inventory)
                   <div class="card-foil bg-white rounded-lg shadow-sm border border-[var(--vault-light)]/10 p-4 text-center">
                        <div class="aspect-square bg-gray-100 rounded mb-3 overflow-hidden">
                            @if ($inventory->boxItem->image)
                                <img src="{{ image_url($inventory->boxItem->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">ไม่มีรูป</div>
                            @endif
                        </div>
                       <p class="font-display font-medium text-gray-800 truncate">{{ $inventory->boxItem->name }}</p>
                        <span @class([
                            'inline-block mt-1 px-2 py-0.5 rounded text-xs',
                            'bg-gray-100 text-gray-700' => $inventory->boxItem->rarity === 'common',
                            'bg-blue-100 text-blue-700' => $inventory->boxItem->rarity === 'rare',
                            'bg-purple-100 text-purple-700' => $inventory->boxItem->rarity === 'epic',
                            'bg-yellow-100 text-yellow-700' => $inventory->boxItem->rarity === 'legendary',
                        ])>
                            {{ ucfirst($inventory->boxItem->rarity) }}
                        </span>
                        <p class="font-mono-data text-sm text-gray-500 mt-1">มูลค่า ฿{{ number_format($inventory->boxItem->market_value, 2) }}</p>

<form action="{{ route('inventory.sell', $inventory) }}" method="POST" class="mt-3"
      onsubmit="return confirm('ยืนยันขายไอเทมนี้คืน?')">
    @csrf
    <button type="submit" class="font-mono-data w-full py-2 bg-red-50 text-red-600 rounded hover:bg-red-100 text-sm font-medium transition">
        ขายคืน ฿{{ number_format($inventory->boxItem->market_value, 2) }}
    </button>
</form>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        คุณยังไม่มีไอเทมในคลัง ลองไปเปิดกล่องสุ่มดูสิ!
                        <br>
                        <a href="{{ route('shop.index') }}" class="text-indigo-600 font-medium">ไปที่ร้านค้า &rarr;</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $inventories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>