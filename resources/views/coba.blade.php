<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="EduCampus dashboard for managing and monitoring your campus data.">

    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style type="text/tailwindcss">
        @theme inline {
            --color-primary: var(--primary);
            --color-primary-hover: var(--primary-hover);
            --color-foreground: var(--foreground);
            --color-secondary: var(--secondary);
            --color-muted: var(--muted);
            --color-border: var(--border);
            --color-card-grey: var(--card-grey);
            --color-card-message: var(--card-message);
            --color-accent-blue: var(--accent-blue);
            --color-accent-teal: var(--accent-teal);
            --color-accent-sky: var(--accent-sky);
            --color-success: var(--success);
            --color-success-light: var(--success-light);
            --color-success-dark: var(--success-dark);
            --color-error: var(--error);
            --color-error-light: var(--error-light);
            --color-error-lighter: var(--error-lighter);
            --color-error-dark: var(--error-dark);
            --color-warning: var(--warning);
            --color-warning-light: var(--warning-light);
            --color-warning-dark: var(--warning-dark);
            --color-info: var(--info);
            --color-info-light: var(--info-light);
            --color-info-dark: var(--info-dark);
            --color-alert: var(--alert);
            --color-alert-light: var(--alert-light);
            --color-alert-dark: var(--alert-dark);
            --color-gray-50: var(--gray-50);
            --color-gray-100: var(--gray-100);
            --color-gray-200: var(--gray-200);
            --color-gray-500: var(--gray-500);
            --color-gray-600: var(--gray-600);
            --color-gray-700: var(--gray-700);
            --font-sans: var(--font-sans);
            --radius-card: 24px;
            --radius-button: 50px;
            --radius-icon: 12px;
            --radius-xl: 16px;
            --radius-2xl: 20px;
            --radius-3xl: 24px;
        }

        :root {
            --primary: #165DFF;
            --primary-hover: #0E4BD9;
            --foreground: #080C1A;
            --secondary: #6A7686;
            --muted: #EFF2F7;
            --border: #F3F4F3;
            --card-grey: #F1F3F6;
            --card-message: #C9E6FC;
            --accent-blue: #C9E6FC;
            --accent-teal: #82D9D7;
            --accent-sky: #DBEAFE;
            --success: #30B22D;
            --success-light: #DCFCE7;
            --success-dark: #166534;
            --error: #ED6B60;
            --error-light: #FEE2E2;
            --error-lighter: #FEF2F2;
            --error-dark: #991B1B;
            --warning: #FED71F;
            --warning-light: #FEF9C3;
            --warning-dark: #854D0E;
            --info: #165DFF;
            --info-light: #DBEAFE;
            --info-dark: #1E40AF;
            --alert: #F97316;
            --alert-light: #FFEDD5;
            --alert-dark: #9A3412;
            --gray-50: #F9FAFB;
            --gray-100: #F1F3F6;
            --gray-200: #E5E7EB;
            --gray-500: #6A7686;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --font-sans: 'Lexend Deca', sans-serif;
        }

        select {
            @apply appearance-none bg-no-repeat cursor-pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-position: right 10px center;
            padding-right: 40px;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

</head>

<body class="font-sans bg-white min-h-screen overflow-x-hidden">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/80 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen max-h-screen flex-1 bg-muted overflow-hidden">
        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-hidden">
            <!-- Top Bar with logo and title -->
            <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-9 bg-primary rounded-xl flex items-center justify-center">
                        <i data-lucide="package-open" class="w-5 h-5 text-white"></i>
                    </div>
                    <h1 class="font-semibold text-xl">StokManajer</h1>
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
                <!-- Main Menu Section -->
                <h3 class="font-medium text-sm text-secondary">Menu Utama</h3>
                <div class="flex flex-col gap-1">

                    <a href="{{ route('dashboard') }}"
                        class="group nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div
                            class="flex items-center rounded-xl p-4 gap-3 hover:bg-muted transition-all group-[.active]:bg-primary/10 group-[.active]:text-primary">
                            <i data-lucide="layout-dashboard"
                                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground"></i>
                            <span
                                class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Dashboard</span>
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

                    <a href="{{ route('admin.master.menu.stokmasuk') }}"
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
                        <a href="{{ route('setting') }}"
                            class="group nav-item {{ request()->routeIs('setting') ? 'active' : '' }}">
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
            </div>

            <!-- Bottom Help Card -->
            <div class="absolute bottom-0 left-0 w-[280px]">
                <div class="flex items-center justify-between border-t bg-white border-border p-5 gap-3">
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

        <!-- MAIN CONTENT -->
        <main class="flex-1 lg:ml-[280px] flex flex-col bg-white min-h-screen overflow-x-hidden">
            <!-- Top Header Bar -->
            <div
                class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-border bg-white px-5 md:px-8">
                <!-- Mobile hamburger -->
                <button onclick="toggleSidebar()" aria-label="Open menu"
                    class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
                    <i data-lucide="menu" class="size-6 text-foreground"></i>
                </button>
                <!-- Page title (shown on desktop) -->
                <h2 class="hidden lg:block font-bold text-2xl text-foreground">Dashboard</h2>
                <!-- Right actions -->
                <div class="flex items-center gap-3">
                    <button onclick="openSearchModal()"
                        class="size-11 flex items-center justify-center rounded-xl ring-1
            ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative">
                        <i data-lucide="search" class="size-6 text-secondary"></i>
                    </button>
                    <button
                        class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative"
                        aria-label="Notifications">
                        <i data-lucide="bell" class="size-6 text-secondary"></i>
                        <span
                            class="absolute -top-1 -right-1 h-5 px-1.5 rounded-full bg-error text-white text-xs font-medium flex items-center justify-center">3</span>
                    </button>
                    <div class="hidden md:flex items-center gap-3 pl-3 border-l border-border">
                        <div class="text-right">
                            <p class="font-semibold text-foreground text-sm">Admin User</p>
                            <p class="text-secondary text-xs">Administrator</p>
                        </div>
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop"
                            alt="Profile" class="size-11 rounded-full object-cover ring-2 ring-border">
                    </div>
                </div>
            </div>

            <div id="search-modal"
                class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
                <div
                    class="bg-white rounded-3xl w-full max-w-2xl max-h-[80vh] overflow-hidden shadow-2xl flex flex-col">
                    <div class="p-4 border-b border-border">
                        <div class="flex items-center gap-3 bg-muted rounded-xl px-4">
                            <i data-lucide="search" class="size-5 text-secondary"></i>
                            <input type="text" id="global-search-input"
                                placeholder="Cari kamar, penyewa, atau menu..."
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

            <!-- Page Content Area -->
            <div class="flex-1 overflow-y-auto p-5 md:p-8">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
                    <div>
                        <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Selamat Datang!</h1>
                        <p class="text-secondary text-sm md:text-base">Welcome back! Here's your order overview for
                            today.</p>
                    </div>
                    <div class="flex items-center gap-2 md:gap-3 ml-auto md:ml-0">
                        <button
                            class="flex items-center gap-2 px-4 py-2.5 ring-1 ring-border hover:ring-primary rounded-button text-foreground font-medium transition-all duration-200 cursor-pointer">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Export Report</span>
                        </button>
                        <button
                            class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-button font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                    <div
                        class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-[6px]">
                                <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="dollar-sign" class="size-6 text-success"></i>
                                </div>
                                <p class="font-medium text-secondary">Total Penjualan</p>
                            </div>
                            <span
                                class="flex items-center text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
                                <i data-lucide="trending-up" class="size-3 mr-1"></i> +12%
                            </span>
                        </div>
                        <p class="font-bold text-[32px] leading-10">Rp 45.2Jt</p>
                        <p class="text-xs text-secondary">Bulan ini</p>
                    </div>

                    <div
                        class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-[6px]">
                                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="shopping-cart" class="size-6 text-primary"></i>
                                </div>
                                <p class="font-medium text-secondary">Total Order</p>
                            </div>
                        </div>
                        <p class="font-bold text-[32px] leading-10">1,240</p>
                        <p class="text-xs text-secondary">Order baru hari ini: <span
                                class="text-foreground font-semibold">24</span>
                        </p>
                    </div>

                    <div
                        class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-[6px]">
                                <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="printer" class="size-6 text-warning"></i>
                                </div>
                                <p class="font-medium text-secondary">Sedang Produksi</p>
                            </div>
                            <span class="size-2 rounded-full bg-warning animate-pulse"></span>
                        </div>
                        <p class="font-bold text-[32px] leading-10">38</p>
                        <p class="text-xs text-secondary">Antrian: <span class="text-foreground font-semibold">12
                                Jobs</span></p>
                    </div>

                    <div
                        class="flex flex-col rounded-2xl border border-border p-6 gap-3 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-[6px]">
                                <div class="size-11 bg-error/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="image" class="size-6 text-error"></i>
                                </div>
                                <p class="font-medium text-secondary">Cek Desain</p>
                            </div>
                        </div>
                        <p class="font-bold text-[32px] leading-10">8</p>
                        <p class="text-xs text-secondary">Perlu verifikasi file</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                    <!-- Attendance Trend Chart -->
                    <div class="flex flex-col rounded-2xl border border-border p-6 gap-6 bg-white">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-[6px]">
                                    <div
                                        class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                                        <i data-lucide="trending-up" class="size-6 text-success"></i>
                                    </div>
                                    <p class="font-medium text-secondary">Attendance Trend</p>
                                </div>
                                <p class="font-bold text-[32px] leading-10">87.5%</p>
                            </div>
                            <button
                                class="flex items-center rounded-3xl border border-border py-3 px-4 gap-2 bg-primary/10 w-fit cursor-pointer hover:bg-primary/20 transition-all duration-300">
                                <i data-lucide="calendar" class="size-5 text-primary"></i>
                                <p class="font-medium text-sm text-primary">Last 7 Days</p>
                            </button>
                        </div>
                        <div class="w-full overflow-x-auto">
                            <div class="min-w-[400px] h-[250px] md:h-[300px]">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Class Distribution Chart -->
                    <div class="flex flex-col rounded-2xl border border-border p-6 gap-4 bg-white shadow-sm">
                        <h3 class="font-bold text-lg mb-2">Status Produksi</h3>
                        <p class="text-sm text-secondary mb-6">Distribusi status order aktif</p>
                        <div class="relative flex items-center justify-center"
                            style="height: 200px; position: relative;">
                            <canvas id="productionChart"></canvas>
                        </div>
                        <div class="mt-6 flex flex-col gap-3">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-primary"></span><span>Cetak</span></div>
                                <span class="font-semibold">45%</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-warning"></span><span>Finishing</span></div>
                                <span class="font-semibold">30%</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-success"></span><span>Siap
                                        Kirim</span></div>
                                <span class="font-semibold">25%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col rounded-2xl border border-border bg-white overflow-hidden shadow-sm">
                    <div
                        class="p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-lg">Order Terbaru</h3>
                            <p class="text-sm text-secondary">5 pesanan masuk terakhir</p>
                        </div>
                        <button onclick="openSearchModal()"
                            class="bg-primary text-white rounded-full py-2.5 px-6 font-bold hover:bg-primary-hover cursor-pointer text-sm transition-colors flex items-center gap-2 self-start sm:self-auto">
                            <i data-lucide="plus" class="size-4"></i> Order Manual
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px]">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="text-left p-4 pl-6 font-semibold text-sm text-secondary">ID Order</th>
                                    <th class="text-left p-4 font-semibold text-sm text-secondary">Pelanggan</th>
                                    <th class="text-left p-4 font-semibold text-sm text-secondary">Produk</th>
                                    <th class="text-left p-4 font-semibold text-sm text-secondary">Status</th>
                                    <th class="text-left p-4 font-semibold text-sm text-secondary">Total</th>
                                    <th class="text-right p-4 pr-6 font-semibold text-sm text-secondary">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr class="hover:bg-muted/30 transition-colors group">
                                    <td class="p-4 pl-6 font-medium">#ORD-001</td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                                AD</div>
                                            <span class="font-medium text-sm">Andi Digital</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm">Banner 3x1m (2pcs)</td>
                                    <td class="p-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> Cetak
                                        </span>
                                    </td>
                                    <td class="p-4 font-medium text-sm">Rp 150.000</td>
                                    <td class="p-4 pr-6 text-right">
                                        <button
                                            class="text-secondary hover:text-primary transition-colors cursor-pointer"
                                            onclick="switchView('order-detail')" title="Lihat Detail">
                                            <i data-lucide="eye" class="size-5"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-muted/30 transition-colors group">
                                    <td class="p-4 pl-6 font-medium">#ORD-002</td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop"
                                                class="size-8 rounded-full object-cover">
                                            <span class="font-medium text-sm">Budi Santoso</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm">Kartu Nama (5 Box)</td>
                                    <td class="p-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning-dark">
                                            <span class="w-1.5 h-1.5 rounded-full bg-warning"></span> Finishing
                                        </span>
                                    </td>
                                    <td class="p-4 font-medium text-sm">Rp 250.000</td>
                                    <td class="p-4 pr-6 text-right">
                                        <button
                                            class="text-secondary hover:text-primary transition-colors cursor-pointer"
                                            onclick="switchView('order-detail')" title="Lihat Detail">
                                            <i data-lucide="eye" class="size-5"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-muted/30 transition-colors group">
                                    <td class="p-4 pl-6 font-medium">#ORD-003</td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="size-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs">
                                                CV</div>
                                            <span class="font-medium text-sm">CV Maju Jaya</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm">Stiker Vinyl A3+ (100lbr)</td>
                                    <td class="p-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-error/10 text-error">
                                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Cek Desain
                                        </span>
                                    </td>
                                    <td class="p-4 font-medium text-sm">Rp 1.200.000</td>
                                    <td class="p-4 pr-6 text-right">
                                        <button
                                            class="text-secondary hover:text-primary transition-colors cursor-pointer"
                                            onclick="switchView('order-detail')" title="Lihat Detail">
                                            <i data-lucide="eye" class="size-5"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-muted/30 transition-colors group">
                                    <td class="p-4 pl-6 font-medium">#ORD-004</td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop"
                                                class="size-8 rounded-full object-cover">
                                            <span class="font-medium text-sm">Siti Aminah</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm">Undangan (300pcs)</td>
                                    <td class="p-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Siap Ambil
                                        </span>
                                    </td>
                                    <td class="p-4 font-medium text-sm">Rp 1.500.000</td>
                                    <td class="p-4 pr-6 text-right">
                                        <button
                                            class="text-secondary hover:text-primary transition-colors cursor-pointer"
                                            onclick="switchView('order-detail')" title="Lihat Detail">
                                            <i data-lucide="eye" class="size-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="footer text-start px-5 md:px-8 py-4 bg-white">
                <p style="font-size: 14px">All Rights Reserved © {{ date('Y') }}.</p>
            </footer>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // =========== Accordion sederhana (untuk Food Menu dll) ===========
            document.querySelectorAll('[data-accordion]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-accordion');
                    const content = document.getElementById(targetId);
                    if (!content) return;

                    const isOpen = !content.classList.contains('hidden');
                    // tutup semua accordion lain (opsional)
                    document.querySelectorAll('[id^="food-menu"], [id^="other-accordion"]').forEach(
                        el => {
                            if (el !== content) el.classList.add('hidden');
                        });

                    content.classList.toggle('hidden');
                    // rotate icon (opsional)
                    const chevron = this.querySelector('i[data-lucide="chevron-down"]');
                    if (chevron) {
                        chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                    }
                });
            });

            // =========== Inisialisasi chart (tetap) ===========
            initializeCharts();
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function closePageNotFoundModal() {
            document.getElementById('page-not-found-modal').classList.add('hidden');
        }

        function openSearchModal() {
            document.getElementById('search-modal').classList.remove('hidden');
            document.getElementById('search-modal').classList.add('flex');
            document.getElementById('global-search-input').focus();
        }

        function closeSearchModal() {
            document.getElementById('search-modal').classList.add('hidden');
            document.getElementById('search-modal').classList.remove('flex');
        }

        function handleGlobalSearch(val) {
            const container = document.getElementById('search-results');
            if (!val) {
                container.innerHTML = '<div class="text-center py-8 text-secondary text-sm">Ketik sesuatu...</div>';
                return;
            }

            // Dummy results based on input
            container.innerHTML = `
            <div onclick="closeSearchModal(); switchPage('rooms', document.querySelectorAll('.nav-item')[1])" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted transition-all cursor-pointer">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center"><i data-lucide="bed-double" class="size-5 text-primary"></i></div>
            <div class="flex-1"><p class="font-medium">Kamar ${val}</p><p class="text-xs text-secondary">Lihat detail kamar</p></div>
            </div>
            <div onclick="closeSearchModal(); switchPage('tenants', document.querySelectorAll('.nav-item')[2])" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted transition-all cursor-pointer">
            <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center"><i data-lucide="user" class="size-5 text-success"></i></div>
            <div class="flex-1"><p class="font-medium">Penyewa "${val}"</p><p class="text-xs text-secondary">Cari di data penyewa</p></div>
            </div>
        `;
            lucide.createIcons();
        }

        function initializeCharts() {
            // Attendance Trend Chart
            new Chart(document.getElementById('attendanceChart'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                            label: 'This Week',
                            data: [85, 92, 78, 95, 88, 82, 90],
                            borderColor: '#165DFF',
                            backgroundColor: 'rgba(22, 93, 255, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Last Week',
                            data: [78, 85, 82, 88, 91, 75, 87],
                            borderColor: '#82D9D7',
                            backgroundColor: 'rgba(130, 217, 215, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    animation: false,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });

            // Class Distribution Chart
            const prodElement = document.getElementById('productionChart');
            if (!prodElement) return; // Prevent error if element not found

            const ctxProd = prodElement.getContext('2d');
            new Chart(ctxProd, {
                type: 'doughnut',
                data: {
                    labels: ['Cetak', 'Finishing', 'Siap Kirim'],
                    datasets: [{
                        data: [45, 30, 25],
                        backgroundColor: ['#165DFF', '#FED71F', '#30B22D'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Important for responsive height
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>
