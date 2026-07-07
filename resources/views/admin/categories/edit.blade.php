<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขหมวดหมู่</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">ชื่อหมวดหมู่</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border-gray-300 rounded">
                        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">รูปภาพ</label>
                        @if ($category->image)
                            <img src="{{ Storage::url($category->image) }}" class="w-20 h-20 object-cover rounded mb-2">
                        @endif
                        <input type="file" name="image" class="w-full">
                        @error('image') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">บันทึก</button>
                    <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>