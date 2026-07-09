<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sandee Shop') }} — ร้านกล่องสุ่มของสะสม</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
</head>
<body class="antialiased" style="background:#F5F4FB;">

    <!-- Header -->
    <header class="sticky top-0 z-40" style="background: var(--void); border-bottom: 1px solid rgba(231,178,76,0.2);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center">
    <img src="{{ asset('images/logo.png') }}" alt="Sandee Shop" class="h-12 w-auto">
</a>

            <nav class="flex items-center gap-3 sm:gap-6">
    @auth
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-semibold" style="background: var(--gold); color: var(--void);">แดชบอร์ด</a>
    @else
        <a href="{{ route('login') }}" class="text-sm font-medium hover:opacity-80 transition hidden sm:inline" style="color: var(--ink-dim);">เข้าสู่ระบบ</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-semibold" style="background: var(--gold); color: var(--void);">สมัครฟรี</a>
    @endauth
</nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative overflow-hidden" style="background: linear-gradient(135deg, var(--void), var(--vault) 65%, var(--violet));">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 text-center relative z-10">
            <p class="font-mono-data text-xs tracking-widest uppercase mb-4" style="color: var(--gold);">Collector's Vault</p>
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-5 leading-tight">
                เปิดกล่องสุ่ม ลุ้นของสะสมสุดพิเศษ
            </h1>
            <p class="text-base sm:text-lg mb-9 max-w-lg mx-auto" style="color: var(--ink-dim);">
                ของสะสมหายาก พร้อมระบบสุ่มที่โปร่งใส ตรวจสอบโอกาสได้ทุกกล่อง
            </p>
            <a href="{{ auth()->check() ? route('shop.index') : route('login') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-lg font-display font-semibold text-lg hover:brightness-110 transition" style="background: var(--gold); color: var(--void);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 12v9H4v-9M2 7h20v5H2V7z"/>
                    <path d="M12 22V7M12 7C10.5 7 9 5.5 9 4a2 2 0 0 1 4-.5"/>
                    <path d="M12 7c1.5 0 3-1.5 3-3a2 2 0 0 0-4-.5"/>
                </svg>
                เริ่มเปิดกล่องสุ่ม
            </a>
        </div>
    </section>

    <!-- Trust Features -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <a href="{{ route('login') }}" class="text-center px-4 py-3 rounded-lg hover:bg-white transition">
                <svg class="mx-auto mb-3" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--violet)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                <h3 class="font-display font-semibold text-gray-800 mb-1">โอกาสโปร่งใส</h3>
                <p class="text-sm text-gray-500">แสดงเปอร์เซ็นต์โอกาสของแต่ละไอเทมชัดเจนทุกกล่อง</p>
            </a>
            <a href="{{ route('login') }}" class="text-center px-4 py-3 rounded-lg hover:bg-white transition">
                <svg class="mx-auto mb-3" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v10M9 9.5c0-1.4 1.3-2.5 3-2.5s3 1 3 2.3c0 3-6 1.5-6 4.5 0 1.4 1.3 2.5 3 2.5s3-1 3-2.3"/>
                </svg>
                <h3 class="font-display font-semibold text-gray-800 mb-1">ขายคืนได้ทันที</h3>
                <p class="text-sm text-gray-500">ไม่ถูกใจไอเทมไหน ขายคืนเป็นเงินในกระเป๋าได้เลย</p>
            </a>
            <a href="{{ route('login') }}" class="text-center px-4 py-3 rounded-lg hover:bg-white transition">
                <svg class="mx-auto mb-3" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#34D399" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/>
                </svg>
                <h3 class="font-display font-semibold text-gray-800 mb-1">เปิดกล่องได้ทันที</h3>
                <p class="text-sm text-gray-500">เติมเงินแล้วลุ้นไอเทมได้ทันที ไม่ต้องรอ</p>
            </a>
        </div>
    </section>
    <!-- Featured Boxes -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-display text-2xl font-bold text-gray-900">กล่องสุ่มมาใหม่</h2>
            <a href="{{ auth()->check() ? route('shop.index') : route('login') }}" class="text-sm font-medium" style="color: var(--violet);">ดูทั้งหมด &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($featuredBoxes as $box)
                <a href="{{ auth()->check() ? route('shop.show', $box) : route('login') }}" class="card-foil group bg-white rounded-xl shadow-sm hover:shadow-xl border border-[var(--vault-light)]/10 transition overflow-hidden">
    <div class="aspect-square bg-gray-100">
        @if ($box->image)
            <img src="{{ image_url($box->image) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">ไม่มีรูป</div>
        @endif
    </div>
    <div class="p-4">
                        <p class="text-xs font-medium mb-1" style="color: var(--violet);">{{ $box->category->name }}</p>
                        <h3 class="font-display font-semibold text-gray-800 mb-2 group-hover:opacity-80 transition">{{ $box->name }}</h3>
                        <div class="flex justify-between items-center">
                            <span class="font-display text-lg font-bold text-gray-900">฿<span class="font-mono-data">{{ number_format($box->price, 2) }}</span></span>
                            <span class="text-xs text-gray-500">เหลือ {{ $box->stock }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">ยังไม่มีกล่องสุ่มในขณะนี้</div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-8" style="background: var(--void);">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
    <img src="{{ asset('images/logo.png') }}" alt="Sandee Shop" class="h-8 w-auto">
</div>
            <p class="text-xs" style="color: var(--ink-dim);">© {{ date('Y') }} Sandee Shop. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>