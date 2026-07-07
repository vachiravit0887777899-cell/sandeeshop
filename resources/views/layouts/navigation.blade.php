<nav x-data="{ open: false }" class="bg-[var(--void)] border-b border-[var(--gold)]/20 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
    <span class="text-2xl">🎁</span>
    <span class="font-display font-bold text-lg tracking-wide text-[var(--ink)]">SANDEE<span class="text-[var(--gold)]">SHOP</span></span>
</a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 text-sm font-medium border-b-2 transition
                       {{ request()->routeIs('dashboard') ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('shop.index') }}"
                       class="px-3 py-2 text-sm font-medium border-b-2 transition
                       {{ request()->routeIs('shop.*') ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                        ร้านค้า
                    </a>
                    <a href="{{ route('wallet.index') }}"
                       class="px-3 py-2 text-sm font-medium border-b-2 transition
                       {{ request()->routeIs('wallet.*') ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                        กระเป๋าเงิน <span class="font-mono-data text-xs">฿{{ number_format(auth()->user()->balance, 2) }}</span>
                    </a>
                    <a href="{{ route('inventory.index') }}"
                       class="px-3 py-2 text-sm font-medium border-b-2 transition
                       {{ request()->routeIs('inventory.*') ? 'text-[var(--gold)] border-[var(--gold)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                        คลังไอเทม
                    </a>

                    @if (auth()->user()->isAdmin())
                        <span class="w-px h-5 bg-[var(--vault-light)] mx-2"></span>
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 text-sm font-medium border-b-2 transition
                           {{ request()->routeIs('admin.dashboard') ? 'text-[var(--violet)] border-[var(--violet)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                            แดชบอร์ดแอดมิน
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                           class="px-3 py-2 text-sm font-medium border-b-2 transition
                           {{ request()->routeIs('admin.categories.*') ? 'text-[var(--violet)] border-[var(--violet)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                            หมวดหมู่
                        </a>
                        <a href="{{ route('admin.boxes.index') }}"
                           class="px-3 py-2 text-sm font-medium border-b-2 transition
                           {{ request()->routeIs('admin.boxes.*') ? 'text-[var(--violet)] border-[var(--violet)]' : 'text-[var(--ink-dim)] border-transparent hover:text-[var(--ink)]' }}">
                            จัดการกล่อง
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <div @click="open = ! open">
                        <button class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md text-[var(--ink-dim)] hover:text-[var(--ink)] transition">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open"
                            x-transition
                            class="absolute z-50 mt-2 w-48 rounded-lg shadow-xl ltr:origin-top-right rtl:origin-top-left end-0 bg-[var(--vault)] border border-[var(--vault-light)]"
                            style="display: none;"
                            @click="open = false">
                        <div class="py-1">
                            <a class="block w-full px-4 py-2 text-start text-sm text-[var(--ink-dim)] hover:text-[var(--ink)] hover:bg-[var(--vault-light)] transition" href="{{ route('profile.edit') }}">โปรไฟล์</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="block w-full px-4 py-2 text-start text-sm text-[var(--ink-dim)] hover:text-[var(--ink)] hover:bg-[var(--vault-light)] transition" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    ออกจากระบบ
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[var(--ink-dim)] hover:text-[var(--ink)] focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-[var(--vault-light)]">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--ink-dim)] hover:text-[var(--ink)]">Dashboard</a>
            <a href="{{ route('shop.index') }}" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--ink-dim)] hover:text-[var(--ink)]">ร้านค้า</a>
            <a href="{{ route('wallet.index') }}" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--ink-dim)] hover:text-[var(--ink)]">กระเป๋าเงิน</a>
            <a href="{{ route('inventory.index') }}" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--ink-dim)] hover:text-[var(--ink)]">คลังไอเทม</a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--violet)]">แดชบอร์ดแอดมิน</a>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-[var(--vault-light)]">
            <div class="px-4">
                <div class="font-medium text-base text-[var(--ink)]">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-[var(--ink-dim)]">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--ink-dim)] hover:text-[var(--ink)]">โปรไฟล์</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block ps-3 pe-4 py-2 text-base font-medium text-[var(--ink-dim)] hover:text-[var(--ink)]">ออกจากระบบ</a>
                </form>
            </div>
        </div>
    </div>
</nav>