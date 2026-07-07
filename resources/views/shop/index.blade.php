<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ร้านกล่องสุ่ม</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ตัวกรองหมวดหมู่ -->
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('shop.index') }}"
                  class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-[var(--void)] text-white' : 'bg-white text-gray-700 border hover:border-[var(--gold)]' }}"
                    ทั้งหมด
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium {{ request('category') === $category->slug ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- กริดกล่องสุ่ม -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($boxes as $box)
                    <a href="{{ route('shop.show', $box) }}" class="card-foil group bg-white rounded-xl shadow-sm hover:shadow-xl border border-[var(--vault-light)]/10 transition overflow-hidden">
                        <div class="aspect-square bg-gray-100">
                            @if ($box->image)
                                <img src="{{ Storage::url($box->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">ไม่มีรูป</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-indigo-600 font-medium mb-1">{{ $box->category->name }}</p>
                           <h3 class="font-display font-semibold text-gray-800 mb-2 group-hover:text-[var(--violet)] transition">{{ $box->name }}</h3>
                            <div class="flex justify-between items-center">
<span class="font-display text-lg font-bold text-gray-900">฿<span class="font-mono-data">{{ number_format($box->price, 2) }}</span></span>
                                <span class="text-xs text-gray-500">เหลือ {{ $box->stock }} กล่อง</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        ยังไม่มีกล่องสุ่มในขณะนี้
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $boxes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>