<div class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-[var(--border)] px-5 md:px-8 
           z-[60] relative"
    style="background-color: var(--header-bg); color: var(--header-text);">
    <!-- Mobile hamburger -->
    <button onclick="toggleSidebar()" aria-label="Open menu"
        class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
        <i data-lucide="menu" class="size-6 text-foreground"></i>
    </button>
    <!-- Page title (shown on desktop) -->
    <h2 class="hidden lg:block font-bold text-2xl text-foreground">{{ $title }}</h2>
    <!-- Right actions -->
    <div class="flex items-center gap-3">
        <button onclick="openSearchModal()"
            class="size-11 flex items-center justify-center rounded-xl ring-1
            ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative">
            <i data-lucide="search" class="size-6 text-secondary"></i>
        </button>

        @role('admin')
            <button
                class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative"
                aria-label="Notifications">

                <i data-lucide="bell" class="size-6 text-secondary"></i>
            </button>
        @endrole
        @role('user')
            {{-- NOTIFIKASI --}}
            <button id="notif-button"
                class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative"
                aria-label="Notifications">

                <i data-lucide="bell" class="size-6 text-secondary"></i>

                {{-- badge dikosongkan, nanti diisi JS --}}
                <span id="notif-count"
                    class="hidden absolute -top-1 -right-1 h-5 px-1.5 rounded-full bg-error text-white text-xs font-medium flex items-center justify-center">
                </span>
            </button>

            {{-- DROPDOWN --}}
            <div id="notif-dropdown"
                class="absolute right-20 top-[70px] w-80 bg-[var(--body-bg)] border border-border rounded-2xl shadow-2xl py-2 opacity-0 scale-95 pointer-events-none transition duration-200 origin-top-right z-[9999]">

                <!-- HEADER -->
                <div class="px-4 py-3 border-b border-border flex justify-between items-center">
                    <p class="text-sm font-semibold text-foreground">Notifikasi</p>

                    <button id="mark-all" class="text-xs text-primary hover:underline">
                        Tandai semua
                    </button>
                </div>

                <!-- LIST -->
                <div id="notif-list" class="max-h-80 overflow-y-auto">
                    <div class="px-4 py-6 text-center text-xs text-secondary">
                        Memuat notifikasi...
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="px-4 py-2 border-t border-border text-center">
                    <button id="lihat-semua" class="text-xs text-primary hover:underline">
                        Lihat semua
                    </button>
                </div>
            </div>
        @endrole

        {{-- User --}}
        <div class="relative flex items-center gap-3 pl-4 border-l border-border z-50">

            <!-- USER TRIGGER -->
            <button id="user-menu-button" class="flex items-center gap-3 group transition-all">

                <div class="text-right leading-tight hidden sm:block">
                    <p class="font-semibold text-foreground text-sm">{{ Auth::user()->username }}</p>
                    <p class="text-secondary text-xs">{{ Auth::user()->email }}</p>
                </div>

                <div class="relative">
                    <img src="{{ Auth::user()->foto_profile }}" alt="Profile"
                        class="size-11 rounded-full object-cover ring-2 ring-border group-hover:ring-primary transition">

                    <span
                        class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-[var(--body-bg)] rounded-full"></span>
                </div>

                <i data-lucide="chevron-down"
                    class="size-4 text-secondary group-hover:text-foreground transition hidden sm:block"></i>
            </button>

            <!-- DROPDOWN -->
            <div id="user-dropdown"
                class="absolute right-0 top-[70px] w-56 bg-[var(--body-bg)] border border-border rounded-2xl shadow-2xl py-2 opacity-0 scale-95 pointer-events-none transition-all duration-200 origin-top-right z-[9999]">

                <!-- USER INFO -->
                <div class="px-4 py-3 border-b border-border">
                    <p class="text-sm font-semibold text-foreground">{{ Auth::user()->username }}</p>
                    <p class="text-xs text-secondary">{{ Auth::user()->email ?? 'No email' }}</p>
                </div>

                <!-- PROFILE -->
                <a href="{{ route('profile') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-foreground hover:bg-muted transition rounded-lg mx-2">
                    <i data-lucide="user" class="size-4"></i>
                    My Profile
                </a>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition rounded-lg mx-2">
                        <i data-lucide="log-out" class="size-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<div id="search-modal"
    class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[80vh] overflow-hidden shadow-2xl flex flex-col">
        <div class="p-4 border-b border-border">
            <div class="flex items-center gap-3 bg-muted rounded-xl px-4">
                <i data-lucide="search" class="size-5 text-secondary"></i>
                <input type="text" id="global-search-input" placeholder="Cari Stok, Barang...."
                    class="flex-1 py-3 bg-transparent outline-none text-foreground"
                    oninput="handleGlobalSearch(this.value)">
                <button onclick="closeSearchModal()"
                    class="p-1 bg-white rounded-lg border border-border text-xs font-bold text-secondary cursor-pointer">ESC</button>
            </div>
        </div>
        <div class="p-4 overflow-y-auto max-h-[60vh]">
            <p class="text-xs font-bold text-secondary uppercase mb-3 px-2">Hasil Pencarian</p>
            <div id="search-results" class="flex flex-col gap-2">
                <div class="text-center py-8 text-secondary text-sm">Ketik sesuatu untuk mencari...</div>
            </div>
        </div>
    </div>
</div>
