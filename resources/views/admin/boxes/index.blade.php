<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">จัดการกล่องสุ่ม</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.boxes.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + เพิ่มกล่องสุ่ม
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">รูป</th>
                            <th class="p-2">ชื่อ</th>
                            <th class="p-2">หมวดหมู่</th>
                            <th class="p-2">ราคา</th>
                            <th class="p-2">สต็อก</th>
                            <th class="p-2">สถานะ</th>
                            <th class="p-2">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boxes as $box)
                            <tr class="border-b">
                                <td class="p-2">
                                    @if ($box->image)
                                        <img src="{{ Storage::url($box->image) }}" class="w-12 h-12 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">ไม่มีรูป</span>
                                    @endif
                                </td>
                                <td class="p-2">{{ $box->name }}</td>
                                <td class="p-2">{{ $box->category->name }}</td>
                                <td class="p-2">฿{{ number_format($box->price, 2) }}</td>
                                <td class="p-2">{{ $box->stock }}</td>
                                <td class="p-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $box->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $box->status === 'active' ? 'เปิดขาย' : 'ปิดขาย' }}
                                    </span>
                                </td>
                                <td class="p-2 space-x-2">
                                    <a href="{{ route('admin.boxes.items.index', $box) }}" class="text-purple-600">ไอเทม</a>
                                    <a href="{{ route('admin.boxes.edit', $box) }}" class="text-blue-600">แก้ไข</a>
                                    <form action="{{ route('admin.boxes.destroy', $box) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $boxes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>