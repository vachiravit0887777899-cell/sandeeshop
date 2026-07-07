<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">จัดการหมวดหมู่</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.categories.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + เพิ่มหมวดหมู่
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">รูป</th>
                            <th class="p-2">ชื่อ</th>
                            <th class="p-2">Slug</th>
                            <th class="p-2">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="border-b">
                                <td class="p-2">
                                    @if ($category->image)
                                        <img src="{{ Storage::url($category->image) }}" class="w-12 h-12 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">ไม่มีรูป</span>
                                    @endif
                                </td>
                                <td class="p-2">{{ $category->name }}</td>
                                <td class="p-2">{{ $category->slug }}</td>
                                <td class="p-2 space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600">แก้ไข</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบ?')">
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
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>