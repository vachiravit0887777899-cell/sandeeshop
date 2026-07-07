<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">เติมเงิน</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="card-foil bg-white rounded-xl shadow-sm border border-[var(--vault-light)]/10 p-6">
                <form action="{{ route('wallet.topup') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block font-medium mb-2">เลือกจำนวนเงินที่ต้องการเติม</label>
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            @foreach ([100, 300, 500, 1000, 3000, 5000] as $preset)
                                <button type="button" onclick="document.getElementById('amount').value = {{ $preset }}"
                                        class="font-mono-data py-3 border-2 border-gray-200 rounded-lg font-semibold hover:border-[var(--gold)] hover:text-[var(--gold)] transition">
                                    ฿{{ $preset }}
                                </button>
                            @endforeach
                        </div>

                        <label class="block text-sm text-gray-600 mb-1">หรือระบุจำนวนเอง</label>
                        <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount') }}"
                               placeholder="ขั้นต่ำ 20 บาท" class="w-full border-gray-300 rounded-lg text-lg">
                        @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white hover:brightness-110 transition" style="background: var(--void);">
                        ยืนยันการเติมเงิน
                    </button>
                    <a href="{{ route('wallet.index') }}" class="block text-center mt-3 text-gray-500">ยกเลิก</a>

                    <p class="text-xs text-gray-400 text-center mt-4">
                        * ขณะนี้เป็นระบบทดสอบ ยังไม่เชื่อมต่อการชำระเงินจริง ยอดเงินจะถูกเติมทันทีเพื่อการทดสอบ
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>