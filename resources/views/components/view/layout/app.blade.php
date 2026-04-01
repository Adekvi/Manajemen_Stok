<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="Managemen Stok.">

    <x-view.include.css />
    @stack('css')

</head>

<body class="font-sans min-h-screen overflow-x-hidden transition-colors duration-300"
    style="background-color: var(--body-bg, #ffffff); color: var(--foreground);">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/80 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen max-h-screen flex-1 bg-muted overflow-hidden">
        <!-- SIDEBAR -->
        <x-view.include.sidebar />

        <!-- SETTINGS FLOATING BUTTON -->
        <button id="theme-toggle-btn"
            class="fixed right-1 top-1/2 -translate-y-1/2 z-[999] w-12 h-12 rounded-lg bg-white border border-gray-200 shadow-md flex items-center justify-center transition-all duration-300 hover:shadow-lg hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary/30">
            <i data-lucide="settings" class="size-6 text-gray-600 animate-spin-slow"></i>
        </button>

        <!-- SETTINGS PANEL -->
        <div id="theme-panel"
            class="fixed inset-y-0 right-0 w-[280px] sm:w-[300px] lg:w-[320px] bg-white/95 backdrop-blur-xl border-l border-gray-200 shadow-2xl z-[1000] transform translate-x-full transition-transform duration-500 ease-out">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-7 py-6 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Tampilan</h3>
                    <p class="text-xs text-gray-500">Personalisasi dashboard</p>
                </div>
                <button id="close-theme-panel" class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <i data-lucide="x" class="size-5 text-gray-600"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="p-7 space-y-8 overflow-y-auto h-[calc(100%-90px)]">
                <!-- ACCENT COLOR -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-4"> Warna Utama </label>
                    <div class="grid grid-cols-5 gap-4">
                        <button class="color-option w-11 h-11 rounded-full shadow hover:scale-110 transition"
                            data-color="#7cb13b" style="background:#7cb13b"> </button>
                        <button class="color-option w-11 h-11 rounded-full shadow hover:scale-110 transition"
                            data-color="#165DFF" style="background:#165DFF"> </button>
                        <button class="color-option w-11 h-11 rounded-full shadow hover:scale-110 transition"
                            data-color="#10B981" style="background:#10B981"> </button>
                        <button class="color-option w-11 h-11 rounded-full shadow hover:scale-110 transition"
                            data-color="#8B5CF6" style="background:#8B5CF6"> </button>
                        <button class="color-option w-11 h-11 rounded-full shadow hover:scale-110 transition"
                            data-color="#F59E0B" style="background:#F59E0B"> </button>
                        <button class="color-option w-11 h-11 rounded-full shadow hover:scale-110 transition"
                            data-color="#EC4899" style="background:#EC4899"> </button>
                    </div>
                </div>
                <!-- MODE -->
                {{-- <div>
                    <label class="block text-sm font-medium text-gray-600 mb-4"> Mode Tampilan </label>
                    <div class="grid grid-cols-2 gap-3">
                        <button data-mode="light"
                            class="mode-option flex items-center justify-center gap-2 py-3 rounded-xl border border-border bg-[var(--body-bg)] text-foreground hover:border-primary transition">
                            <i data-lucide="sun" class="size-4"></i> Light
                        </button>
                        <button data-mode="dark"
                            class="mode-option flex items-center justify-center gap-2 py-3 rounded-xl border border-border bg-[var(--body-bg)] text-foreground hover:border-primary transition">
                            <i data-lucide="moon" class="size-4"></i> Dark
                        </button>
                    </div>
                </div> --}}
                <!-- FONT -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-3"> Jenis Huruf </label>
                    <select id="font-family-select"
                        class="w-full px-4 py-3 rounded-xl border border-border bg-[var(--body-bg)] text-foreground focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition">
                        <option value="'Lexend Deca', system-ui, sans-serif"> Lexend Deca </option>
                        <option value="'Inter', system-ui, sans-serif"> Inter </option>
                        <option value="'Manrope', system-ui, sans-serif"> Manrope </option>
                        <option value="'Poppins', system-ui, sans-serif"> Poppins </option>
                        <option value="'Roboto', system-ui, sans-serif"> Roboto </option>
                    </select>
                </div>
                <!-- RESET -->
                <button id="reset-all-settings"
                    class="w-full py-3 rounded-xl border border-red-200 text-red-600 font-medium hover:bg-red-50 transition">
                    Reset
                </button>
            </div>

        </div>

        <!-- NOTIFIKASI -->
        {{-- PANEL NOTIF --}}
        <div id="notif-panel"
            class="fixed top-0 right-0 h-full w-[380px] bg-[var(--body-bg)] border-l border-border shadow-2xl transform translate-x-full transition duration-300 z-[9999] flex flex-col">

            <!-- HEADER -->
            <div class="px-4 py-4 border-b border-border flex justify-between items-center">
                <p class="font-semibold text-foreground">Semua Notifikasi</p>
                <button onclick="closeNotifPanel()" class="text-secondary hover:text-foreground">
                    ✕
                </button>
            </div>

            <!-- LIST -->
            <div id="panel-list" class="flex-1 overflow-y-auto">
                <div class="px-4 py-6 text-center text-xs text-secondary">
                    Memuat notifikasi...
                </div>
            </div>
        </div>

        <div id="notif-detail" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[10000]">

            <div class="bg-[var(--body-bg)] rounded-2xl w-full max-w-md p-6 shadow-xl">

                <h2 id="detail-judul" class="font-bold text-lg mb-2"></h2>
                <p id="detail-deskripsi" class="text-sm text-secondary mb-3"></p>
                <span id="detail-tgl" class="text-xs text-secondary"></span>

                <div class="mt-4 text-right">
                    <button onclick="closeDetail()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm">
                        Tutup
                    </button>
                </div>

            </div>
        </div>

        <!-- MAIN CONTENT -->
        <main class="flex-1 lg:ml-[280px] flex flex-col min-h-screen overflow-x-hidden transition-colors duration-300"
            style="background-color: var(--body-bg);">
            <!-- Top Header Bar -->
            <x-view.include.header />

            <!-- Page Content Area -->
            <div class="flex-1 overflow-y-auto p-5 md:p-8" style="background-color: var(--body-bg);">
                {{ $slot }}
            </div>

            <x-view.include.footer />

        </main>

    </div>

    <!-- Page Not Found Modal -->
    <div id="page-not-found-modal"
        class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-card p-6 max-w-sm w-full text-center">
            <div class="w-16 h-16 bg-warning-light rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-warning-dark"></i>
            </div>
            <h3 class="text-foreground text-xl font-bold mb-2">Page Not Available</h3>
            <p class="text-gray-500 text-sm mb-6">This page hasn't been created yet. Generate it using the chat!</p>
            <button onclick="closePageNotFoundModal()"
                class="w-full px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
                Got it
            </button>
        </div>
    </div>

    @stack('js')
    <x-view.include.js />
</body>

</html>
