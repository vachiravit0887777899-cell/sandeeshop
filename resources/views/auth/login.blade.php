<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="font-display text-2xl font-bold" style="color: var(--brand-brown);">ยินดีต้อนรับกลับมา 👋</h1>
        <p class="text-sm text-gray-500 mt-1">เข้าสู่ระบบเพื่อเปิดกล่องสุ่มและสะสมไอเทมสุดพิเศษ</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="อีเมล" />
            <x-text-input id="email" class="block mt-1 w-full focus:!border-[var(--brand-pink)] focus:!ring-[var(--brand-pink)]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="รหัสผ่าน" />
            <x-text-input id="password" class="block mt-1 w-full focus:!border-[var(--brand-pink)] focus:!ring-[var(--brand-pink)]" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[var(--brand-pink)] shadow-sm focus:ring-[var(--brand-pink)]" name="remember">
                <span class="ms-2 text-sm text-gray-600">จดจำฉันไว้</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-500 hover:text-gray-800" href="{{ route('password.request') }}">
                    ลืมรหัสผ่าน?
                </a>
            @endif

            <x-primary-button class="!bg-[var(--brand-brown)] hover:!brightness-110">
                เข้าสู่ระบบ
            </x-primary-button>
        </div>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        ยังไม่มีบัญชี?
        <a href="{{ route('register') }}" class="font-medium underline" style="color: var(--brand-pink);">สมัครสมาชิกฟรี</a>
    </p>
</x-guest-layout>