<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            เพิ่มไอเทม — {{ $box->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.boxes.items.store', $box) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">ชื่อไอเทม</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded">
                        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">รูปภาพ</label>
                        <input type="file" name="image" class="w-full">
                        @error('image') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">ระดับความหายาก (Rarity)</label>
                        <select name="rarity" class="w-full border-gray-300 rounded">
                            <option value="common">Common (ธรรมดา)</option>
                            <option value="rare">Rare (หายาก)</option>
                            <option value="epic">Epic (หายากมาก)</option>
                            <option value="legendary">Legendary (ในตำนาน)</option>
                        </select>
                        @error('rarity') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">มูลค่าไอเทม (บาท)</label>
                        <input type="number" step="0.01" name="market_value" value="{{ old('market_value') }}" class="w-full border-gray-300 rounded">
                        @error('market_value') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Probability (% โอกาสออก)</label>
                        <input type="number" step="0.01" name="probability" value="{{ old('probability') }}" class="w-full border-gray-300 rounded">
                        <p class="text-sm text-gray-500 mt-1">รวมของทุกไอเทมในกล่องนี้ต้องไม่เกิน 100%</p>
                        @error('probability') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">สต็อก (จำนวนไอเทม)</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" class="w-full border-gray-300 rounded">
                        @error('stock') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">บันทึก</button>
                    <a href="{{ route('admin.boxes.items.index', $box) }}" class="ml-2 text-gray-600">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>