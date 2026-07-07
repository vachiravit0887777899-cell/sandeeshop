<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $box->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- รูปกล่อง -->
                    <div id="box-visual" class="aspect-square bg-gray-100 rounded-lg overflow-hidden relative">
                        @if ($box->image)
                            <img src="{{ Storage::url($box->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">ไม่มีรูป</div>
                        @endif
                    </div>

                    <!-- ข้อมูลกล่อง -->
                    <div>
                        <p class="text-sm text-[var(--violet)] font-medium mb-2">{{ $box->category->name }}</p>
                        <h1 class="font-display text-2xl font-bold text-gray-900 mb-4">{{ $box->name }}</h1>
                        <p class="text-gray-600 mb-6">{{ $box->description }}</p>

                        <div class="flex items-center gap-4 mb-6">
                            <span class="font-display text-3xl font-bold text-gray-900">฿<span class="font-mono-data">{{ number_format($box->price, 2) }}</span></span>
                            <span class="text-sm text-gray-500">เหลือ {{ $box->stock }} กล่อง</span>
                        </div>

                        @if ($box->stock > 0)
                            <button id="open-box-btn" data-box-id="{{ $box->id }}"
                                    class="w-full py-3 text-white rounded-lg font-semibold hover:brightness-110 transition"
                                    style="background: var(--void);">
                                🎁 เปิดกล่องสุ่มนี้ (ยอดเงิน: ฿<span class="font-mono-data">{{ number_format(auth()->user()->balance, 2) }}</span>)
                            </button>
                            <div id="open-box-error" class="text-red-600 text-sm mt-2 text-center hidden"></div>
                        @else
                            <button disabled class="w-full py-3 bg-gray-300 text-gray-500 rounded-lg font-semibold cursor-not-allowed">
                                สินค้าหมด
                            </button>
                        @endif
                    </div>
                </div>

                <!-- รายการไอเทมในกล่อง -->
                <div class="mt-10">
                    <h2 class="font-display text-lg font-semibold text-gray-800 mb-4">ไอเทมที่อาจได้รับ</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($box->items as $item)
                            <div class="card-foil border rounded-lg p-3 text-center" data-item-strip
                                 data-name="{{ $item->name }}"
                                 data-image="{{ $item->image ? asset('storage/'.$item->image) : '' }}"
                                 data-rarity="{{ $item->rarity }}">
                                <div class="aspect-square bg-gray-100 rounded mb-2 overflow-hidden">
                                    @if ($item->image)
                                        <img src="{{ Storage::url($item->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">ไม่มีรูป</div>
                                    @endif
                                </div>
                                <p class="font-display text-sm font-medium text-gray-800 truncate">{{ $item->name }}</p>
                                <span @class([
                                    'inline-block mt-1 px-2 py-0.5 rounded text-xs',
                                    'bg-gray-100 text-gray-700' => $item->rarity === 'common',
                                    'bg-blue-100 text-blue-700' => $item->rarity === 'rare',
                                    'bg-purple-100 text-purple-700' => $item->rarity === 'epic',
                                    'bg-yellow-100 text-yellow-700' => $item->rarity === 'legendary',
                                ])>
                                    {{ ucfirst($item->rarity) }}
                                </span>
                                <p class="font-mono-data text-xs text-gray-500 mt-1">{{ $item->probability }}% โอกาส</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal เต็มจอสำหรับแอนิเมชันเปิดกล่อง -->
    <div id="opening-overlay" class="fixed inset-0 z-50 hidden items-center justify-center" style="background: rgba(20,17,43,0.92);">
        <div class="w-full max-w-lg mx-4 text-center">

            <!-- Stage 1: กล่องสั่น -->
            <div id="stage-shake" class="hidden">
                <div id="shake-box" class="text-8xl select-none">🎁</div>
                <p class="font-display text-white text-lg mt-6 tracking-wide animate-pulse">กำลังเปิดกล่อง...</p>
            </div>

            <!-- Stage 2: แถบสปิน -->
            <div id="stage-spin" class="hidden">
                <p class="font-display text-white text-sm mb-3 tracking-widest">🎲 กำลังสุ่ม 🎲</p>
                <div class="relative bg-[var(--vault)] rounded-xl overflow-hidden border-2 border-[var(--gold)]/40" style="height: 140px;">
                    <!-- เส้นชี้ตรงกลาง -->
                    <div class="absolute left-1/2 top-0 bottom-0 w-1 bg-[var(--gold)] z-10" style="transform: translateX(-50%);"></div>
                    <div id="spin-track" class="flex items-center h-full" style="gap: 12px; padding: 0 12px;"></div>
                </div>
            </div>

            <!-- Stage 3: ผลลัพธ์ -->
            <div id="stage-result" class="hidden">
                <p class="font-display text-white text-sm mb-4 tracking-widest">🎉 คุณได้รับ 🎉</p>
                <div id="result-glow" class="w-40 h-40 mx-auto rounded-xl mb-4 overflow-hidden flex items-center justify-center bg-[var(--vault)]">
                    <div id="result-image" class="w-full h-full"></div>
                </div>
                <p id="result-name" class="font-display text-2xl font-bold text-white mb-1"></p>
                <span id="result-rarity" class="inline-block px-3 py-1 rounded text-sm font-medium mb-4"></span>
                <p id="result-value" class="font-mono-data text-[var(--ink-dim)] mb-8"></p>
                <button onclick="location.reload()" class="px-8 py-3 bg-[var(--gold)] text-[var(--void)] rounded-lg font-semibold hover:brightness-110 transition">
                    รับไอเทม
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes box-shake {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            10% { transform: translateX(-8px) rotate(-6deg); }
            20% { transform: translateX(8px) rotate(6deg); }
            30% { transform: translateX(-8px) rotate(-6deg); }
            40% { transform: translateX(8px) rotate(6deg); }
            50% { transform: translateX(-6px) rotate(-4deg); }
            60% { transform: translateX(6px) rotate(4deg); }
            70% { transform: translateX(-4px) rotate(-3deg); }
            80% { transform: translateX(4px) rotate(3deg); }
            90% { transform: translateX(-2px) rotate(-1deg); }
        }
        #shake-box { animation: box-shake 0.5s infinite; }

        @keyframes glow-common { 0%,100% { box-shadow: 0 0 0 rgba(156,163,175,0); } }
        @keyframes glow-rare { 0%,100% { box-shadow: 0 0 30px rgba(59,130,246,0.5);} 50% { box-shadow: 0 0 50px rgba(59,130,246,0.8);} }
        @keyframes glow-epic { 0%,100% { box-shadow: 0 0 30px rgba(168,85,247,0.6);} 50% { box-shadow: 0 0 60px rgba(168,85,247,0.9);} }
        @keyframes glow-legendary { 0%,100% { box-shadow: 0 0 40px rgba(231,178,76,0.7);} 50% { box-shadow: 0 0 70px rgba(231,178,76,1);} }

        .glow-rare { animation: glow-rare 1.2s infinite; }
        .glow-epic { animation: glow-epic 1.1s infinite; }
        .glow-legendary { animation: glow-legendary 1s infinite; }

        @keyframes spin-scroll {
            from { transform: translateX(0); }
            to { transform: translateX(var(--scroll-distance)); }
        }
        #spin-track.scrolling {
            animation: spin-scroll 2.8s cubic-bezier(0.15, 0.85, 0.3, 1) forwards;
        }
    </style>

    <script>
        document.getElementById('open-box-btn')?.addEventListener('click', function () {
            const btn = this;
            const boxId = btn.dataset.boxId;
            const errorBox = document.getElementById('open-box-error');

            btn.disabled = true;
            errorBox.classList.add('hidden');

            fetch(`/shop/${boxId}/open`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    runOpeningSequence(data.item);
                } else {
                    errorBox.textContent = data.message;
                    errorBox.classList.remove('hidden');
                    btn.disabled = false;
                }
            })
            .catch(() => {
                errorBox.textContent = 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
                errorBox.classList.remove('hidden');
                btn.disabled = false;
            });
        });

        function runOpeningSequence(item) {
            const overlay = document.getElementById('opening-overlay');
            const stageShake = document.getElementById('stage-shake');
            const stageSpin = document.getElementById('stage-spin');
            const stageResult = document.getElementById('stage-result');

            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            stageShake.classList.remove('hidden');

            // รวบรวมไอเทมทั้งหมดในกล่อง (จากการ์ดด้านล่างหน้า) มาทำแถบสปิน
            const allItems = Array.from(document.querySelectorAll('[data-item-strip]')).map(el => ({
                name: el.dataset.name,
                image: el.dataset.image,
                rarity: el.dataset.rarity,
            }));

            // Stage 1: สั่น 1.5 วินาที
            setTimeout(() => {
                stageShake.classList.add('hidden');
                stageSpin.classList.remove('hidden');
                buildSpinTrack(allItems, item);
            }, 1500);
        }

        function buildSpinTrack(allItems, finalItem) {
            const track = document.getElementById('spin-track');
            track.innerHTML = '';
            track.classList.remove('scrolling');

            const rarityColor = { common: '#9CA3AF', rare: '#3B82F6', epic: '#A855F7', legendary: '#E7B24C' };

            // สร้างไอเทมสุ่ม 25 ชิ้น + ไอเทมจริงแทรกที่ตำแหน่งที่ 20 (นับจาก 0)
            const sequence = [];
            const winIndex = 20;
            for (let i = 0; i < 25; i++) {
                if (i === winIndex) {
                    sequence.push(finalItem);
                } else {
                    sequence.push(allItems.length ? allItems[Math.floor(Math.random() * allItems.length)] : finalItem);
                }
            }

            const cardWidth = 96;
            sequence.forEach((it) => {
                const card = document.createElement('div');
                card.className = 'shrink-0 rounded-lg overflow-hidden bg-[var(--void)] flex flex-col items-center justify-center text-center';
                card.style.width = cardWidth + 'px';
                card.style.height = '116px';
                card.style.border = `2px solid ${rarityColor[it.rarity] || '#9CA3AF'}`;
                card.innerHTML = it.image
                    ? `<img src="${it.image}" class="w-14 h-14 object-cover rounded mb-1">`
                    : `<div class="w-14 h-14 flex items-center justify-center text-xl mb-1">🎁</div>`;
                track.appendChild(card);
            });

            // คำนวณระยะเลื่อนให้ไอเทมที่ตำแหน่ง winIndex หยุดตรงกลาง (เส้นชี้)
            const trackWidthToWin = winIndex * (cardWidth + 12) + (cardWidth / 2);
            const containerCenter = track.parentElement.clientWidth / 2;
            const distance = -(trackWidthToWin - containerCenter);

            track.style.setProperty('--scroll-distance', `${distance}px`);

            requestAnimationFrame(() => {
                track.classList.add('scrolling');
            });

            // หลังสปิน 2.8 วินาที ไปหน้าผลลัพธ์
            setTimeout(() => {
                showResult(finalItem);
            }, 3000);
        }

        function showResult(item) {
            document.getElementById('stage-spin').classList.add('hidden');
            const stageResult = document.getElementById('stage-result');
            stageResult.classList.remove('hidden');

            const rarityStyles = {
                common: { badge: 'bg-gray-100 text-gray-700', glow: '' },
                rare: { badge: 'bg-blue-100 text-blue-700', glow: 'glow-rare' },
                epic: { badge: 'bg-purple-100 text-purple-700', glow: 'glow-epic' },
                legendary: { badge: 'bg-yellow-100 text-yellow-700', glow: 'glow-legendary' },
            };
            const style = rarityStyles[item.rarity] || rarityStyles.common;

            const resultGlow = document.getElementById('result-glow');
            resultGlow.className = `w-40 h-40 mx-auto rounded-xl mb-4 overflow-hidden flex items-center justify-center bg-[var(--vault)] ${style.glow}`;

            document.getElementById('result-image').innerHTML = item.image
                ? `<img src="${item.image}" class="w-full h-full object-cover">`
                : '<div class="text-5xl">🎁</div>';
            document.getElementById('result-name').textContent = item.name;
            document.getElementById('result-rarity').textContent = item.rarity.toUpperCase();
            document.getElementById('result-rarity').className = 'inline-block px-3 py-1 rounded text-sm font-medium mb-4 ' + style.badge;
            document.getElementById('result-value').textContent = `มูลค่า ฿${Number(item.market_value).toLocaleString()}`;
        }
    </script>
</x-app-layout>