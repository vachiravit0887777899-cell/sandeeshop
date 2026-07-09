<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            จัดการไอเทม — {{ $box->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('admin.boxes.index') }}" class="text-gray-600">&larr; กลับไปหน้ากล่องสุ่ม</a>

                    <div class="text-sm font-medium {{ $totalProbability == 100 ? 'text-green-600' : 'text-orange-600' }}">
                        รวม Probability: {{ $totalProbability }}% 
                        @if ($totalProbability < 100)
                            (เหลืออีก {{ 100 - $totalProbability }}%)
                        @elseif ($totalProbability == 100)
                            ✅ ครบ 100%
                        @endif
                    </div>
                </div>

                <a href="{{ route('admin.boxes.items.create', $box) }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + เพิ่มไอเทม
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">รูป</th>
                            <th class="p-2">ชื่อ</th>
                            <th class="p-2">Rarity</th>
                            <th class="p-2">มูลค่า</th>
                            <th class="p-2">Probability</th>
                            <th class="p-2">สต็อก</th>
                            <th class="p-2">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="border-b">
                                <td class="p-2">
                                    @if ($item->image)
                                        <img src="{{ image_url($item->image) }}" class="w-12 h-12 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">ไม่มีรูป</span>
                                    @endif
                                </td>
                                <td class="p-2">{{ $item->name }}</td>
                                <td class="p-2">
                                    <span @class([
                                        'px-2 py-1 rounded text-xs',
                                        'bg-gray-100 text-gray-700' => $item->rarity === 'common',
                                        'bg-blue-100 text-blue-700' => $item->rarity === 'rare',
                                        'bg-purple-100 text-purple-700' => $item->rarity === 'epic',
                                        'bg-yellow-100 text-yellow-700' => $item->rarity === 'legendary',
                                    ])>
                                        {{ ucfirst($item->rarity) }}
                                    </span>
                                </td>
                                <td class="p-2">฿{{ number_format($item->market_value, 2) }}</td>
                                <td class="p-2">{{ $item->probability }}%</td>
                                <td class="p-2">{{ $item->stock }}</td>
                                <td class="p-2 space-x-2">
                                    <a href="{{ route('admin.items.edit', $item) }}" class="text-blue-600">แก้ไข</a>
                                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>