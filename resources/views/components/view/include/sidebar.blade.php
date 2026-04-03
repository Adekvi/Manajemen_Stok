<aside id="sidebar"
    class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 
           z-[100] border-r border-[var(--border)] 
           transform -translate-x-full lg:translate-x-0 
           transition-transform duration-300 overflow-hidden"
    style="background-color: var(--sidebar-bg); color: var(--sidebar-text);">

    <!-- Top Bar with logo and title -->
    <div class="flex items-center justify-between border-b border-[var(--border)] h-[90px] px-5 gap-3">
        <div class="flex items-center gap-3">
            <div class="w-11 h-9 rounded-xl overflow-hidden flex items-center justify-center bg-[var(--primary)]">

                @if ($setting?->logo && file_exists(public_path('setting/logo/' . $setting->logo)))
                    <img src="{{ asset('setting/logo/' . $setting->logo) }}" alt="Logo Toko"
                        class="w-full h-full object-cover">
                @else
                    <i data-lucide="package-open" class="w-5 h-5 text-white"></i>
                @endif

            </div>

            <h1 class="font-semibold text-xl text-[var(--foreground)]">StokManajer</h1>
        </div>
        <div class="flex gap-2">
            <button onclick="toggleSidebar()" aria-label="Close sidebar"
                class="lg:hidden size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
                <i data-lucide="x" class="size-6 text-secondary"></i>
            </button>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col p-5 pb-28 gap-6 overflow-y-auto flex-1">
        @if (Auth::check())
            @if (Auth::user()->role == 'admin')
                <!-- Main Menu Section -->
                <h3 class="font-medium text-sm text-secondary">Menu Utama</h3>
                <div class="flex flex-col gap-1">

                    <a href="{{ route('admin.dashboard') }}"
                        class="group nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="layout-dashboard"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Dashboard</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.master.produk') }}"
                        class="group nav-item {{ request()->routeIs('admin.master.produk') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="package-plus"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Produk</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.master.menu.stokmasuk') }}"
                        class="group nav-item {{ request()->routeIs('admin.master.menu.stokmasuk') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="square-pen"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Stok
                                Masuk</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.master.menu.stokkeluar') }}"
                        class="group nav-item {{ request()->routeIs('admin.master.menu.stokkeluar') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="square-arrow-right-exit"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Stok
                                Keluar</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.master.menu.kartustok') }}"
                        class="group nav-item {{ request()->routeIs('admin.master.menu.kartustok') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="credit-card"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Kartu
                                Stok</span>
                        </div>
                    </a>

                    <!-- Food Menu (accordion) – contoh, sesuaikan route sesuai kebutuhan -->
                    {{-- <div>
                <button data-accordion="food-menu"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-card hover:bg-gray-50 cursor-pointer transition-all duration-200 group">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="utensils" class="size-6 text-secondary group-hover:text-foreground"></i>
                        <span class="font-medium text-secondary group-hover:text-foreground">Food Menu</span>
                    </div>
                    <i data-lucide="chevron-down"
                        class="w-4 h-4 text-gray-600 group-hover:text-primary transition-transform duration-200"></i>
                </button>
                <div id="food-menu" class="ml-4 mt-2 space-y-1 hidden">
                    <!-- tambahkan route asli nanti -->
                    <a href="#" class="group cursor-pointer">
                        <div class="px-4 py-2 rounded-card hover:bg-gray-50"><span
                                class="text-foreground text-sm group-hover:text-primary">All Items</span></div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="px-4 py-2 rounded-card hover:bg-gray-50"><span
                                class="text-foreground text-sm group-hover:text-primary">Add New Item</span></div>
                    </a>
                    <a href="#" class="group cursor-pointer">
                        <div class="px-4 py-2 rounded-card hover:bg-gray-50"><span
                                class="text-foreground text-sm group-hover:text-primary">Categories</span></div>
                    </a>
                </div>
            </div> --}}

                </div>

                <!-- Management Section -->
                <div class="flex flex-col gap-4">
                    <h3 class="font-medium text-sm text-secondary">Management</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.master.menu.report') }}"
                            class="group nav-item {{ request()->routeIs('admin.master.menu.report') ? 'active' : '' }}">
                            <div
                                class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                                <i data-lucide="bar-chart-3"
                                    class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                                <span
                                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Reports</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.pengguna') }}"
                            class="group nav-item {{ request()->routeIs('admin.pengguna') ? 'active' : '' }}">
                            <div
                                class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                                <i data-lucide="contact"
                                    class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                                <span
                                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Pengguna</span>
                            </div>
                        </a>
                        <a href="{{ route('menu.setting') }}"
                            class="group nav-item {{ request()->routeIs('menu.setting') ? 'active' : '' }}">
                            <div
                                class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                                <i data-lucide="settings"
                                    class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                                <span
                                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Settings</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Management Section -->
                <div class="flex flex-col gap-4">
                    <h3 class="font-medium text-sm text-secondary">Lainnya</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.master.menu.info') }}"
                            class="group nav-item {{ request()->routeIs('admin.master.menu.info') ? 'active' : '' }}">
                            <div
                                class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                                <i data-lucide="bell"
                                    class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                                <span
                                    class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Pengumuman</span>
                            </div>
                        </a>
                    </div>
                </div>
            @elseif (Auth::user()->role == 'user')
                <!-- Main Menu Section -->
                <h3 class="font-medium text-sm text-secondary">Menu Utama</h3>
                <div class="flex flex-col gap-1">

                    <a href="{{ route('user.dashboard') }}"
                        class="group nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="layout-dashboard"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Dashboard</span>
                        </div>
                    </a>

                    <a href="{{ route('user.produk') }}"
                        class="group nav-item {{ request()->routeIs('user.produk') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="package-plus"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Produk</span>
                        </div>
                    </a>

                    <a href="{{ route('user.stok.keluar') }}"
                        class="group nav-item {{ request()->routeIs('user.stok.keluar') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="square-arrow-right-exit"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Stok
                                Keluar</span>
                        </div>
                    </a>

                </div>
            @endif
        @endif
    </div>

    <!-- Bottom Help Card -->
    <div class="absolute bottom-0 left-0 w-[280px]">
        <div
            class="flex items-center justify-between border-t border-[var(--border)] bg-[var(--help-card-bg)] p-5 gap-3">
            <div class="min-w-0">
                <p class="font-semibold text-foreground">Need help?</p>
                <a href="#" class="cursor-pointer"><span
                        class="text-sm text-secondary hover:text-primary hover:underline transition-all duration-300">Contact
                        support</span></a>
            </div>
            <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="message-circle-question" class="size-6 text-primary"></i>
            </div>
        </div>
    </div>
</aside>
