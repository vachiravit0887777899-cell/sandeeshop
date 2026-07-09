<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขกล่องสุ่ม</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.boxes.update', $box) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">หมวดหมู่</label>
                        <select name="category_id" class="w-full border-gray-300 rounded">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $box->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">ชื่อกล่องสุ่ม</label>
                        <input type="text" name="name" value="{{ old('name', $box->name) }}" class="w-full border-gray-300 rounded">
                        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">คำอธิบาย</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded">{{ old('description', $box->description) }}</textarea>
                        @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">รูปภาพ</label>
                        @if ($box->image)
                            <img src="{{ image_url($box->image) }}" class="w-20 h-20 object-cover rounded mb-2">
                        @endif
                        <input type="file" name="image" class="w-full">
                        @error('image') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">ราคา (บาท)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $box->price) }}" class="w-full border-gray-300 rounded">
                        @error('price') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">สต็อก (จำนวนกล่อง)</label>
                        <input type="number" name="stock" value="{{ old('stock', $box->stock) }}" class="w-full border-gray-300 rounded">
                        @error('stock') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">สถานะ</label>
                        <select name="status" class="w-full border-gray-300 rounded">
                            <option value="active" {{ old('status', $box->status) === 'active' ? 'selected' : '' }}>เปิดขาย</option>
                            <option value="inactive" {{ old('status', $box->status) === 'inactive' ? 'selected' : '' }}>ปิดขาย</option>
                        </select>
                        @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">บันทึก</button>
                    <a href="{{ route('admin.boxes.index') }}" class="ml-2 text-gray-600">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>