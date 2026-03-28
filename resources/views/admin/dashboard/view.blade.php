<x-view.layout.app title="Dashboard">

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
            class="flex flex-col rounded-2xl border border-border p-6 gap-3  shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-[6px]">
                    <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="package-open" class="size-6 text-success"></i>
                    </div>
                    <p class="font-medium text-secondary">Total Produk</p>
                </div>
                <span class="flex items-center text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
                    <i data-lucide="trending-up" class="size-3 mr-1"></i> +12%
                </span>
            </div>
            <p class="font-bold text-[32px] leading-10">{{ $produkAll ?? '-' }}</p>
            <p class="text-xs text-secondary">Semua produk</p>
        </div>

        <div
            class="flex flex-col rounded-2xl border border-border p-6 gap-3  shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-[6px]">
                    <div class="size-11 bg-info/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="package-plus" class="size-6 text-info"></i>
                    </div>
                    <p class="font-medium text-secondary">Total Stok Masuk</p>
                </div>
            </div>
            <p class="font-bold text-[32px] leading-10">{{ $ttlMasuk ?? '-' }}</p>
            <p class="text-xs text-secondary">Order baru hari ini: <span class="text-foreground font-semibold">24</span>
            </p>
        </div>

        <div
            class="flex flex-col rounded-2xl border border-border p-6 gap-3  shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-[6px]">
                    <div class="size-11 bg-warning/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="package-minus" class="size-6 text-warning"></i>
                    </div>
                    <p class="font-medium text-secondary">Total Stok Keluar</p>
                </div>
                <span class="size-2 rounded-full bg-warning animate-pulse"></span>
            </div>
            <p class="font-bold text-[32px] leading-10">{{ $ttlKeluar ?? '-' }}</p>
            <p class="text-xs text-secondary">Antrian: <span class="text-foreground font-semibold">12 Jobs</span></p>
        </div>

        <div
            class="flex flex-col rounded-2xl border border-border p-6 gap-3  shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-[6px]">
                    <div class="size-11 bg-error/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="package-x" class="size-6 text-error"></i>
                    </div>
                    <p class="font-medium text-secondary">Report Stok</p>
                </div>
            </div>
            <p class="font-bold text-[32px] leading-10"></p>
            <p class="text-xs text-secondary">Perlu verifikasi file</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Attendance Trend Chart -->
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-6 ">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-[6px]">
                        <div class="size-11 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="trending-up" class="size-6 text-success"></i>
                        </div>
                        <p class="font-medium text-secondary">Attendance Trend</p>
                    </div>
                    <p class="font-bold text-[32px] leading-10">
                        {{ isset($attendancePercentage) ? number_format($attendancePercentage, 1) : '0.0' }}%
                    </p>
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
        <div class="flex flex-col rounded-2xl border border-border p-6 gap-4  shadow-sm">
            <h3 class="font-bold text-lg mb-2">Status Stok</h3>
            <p class="text-sm text-secondary mb-6">Distribusi Stok</p>
            <div class="relative flex items-center justify-center" style="height: 200px; position: relative;">
                <canvas id="productionChart"></canvas>
            </div>
            @php
                $total = array_sum($productionStats);
            @endphp

            <div class="mt-6 flex flex-col gap-3">

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                        <span>Draft</span>
                    </div>
                    <span class="font-semibold">
                        {{ $total ? round(($productionStats['draft'] / $total) * 100) : 0 }}%
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span>Posted</span>
                    </div>
                    <span class="font-semibold">
                        {{ $total ? round(($productionStats['posted'] / $total) * 100) : 0 }}%
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span>Cancelled</span>
                    </div>
                    <span class="font-semibold">
                        {{ $total ? round(($productionStats['cancelled'] / $total) * 100) : 0 }}%
                    </span>
                </div>

            </div>
        </div>
    </div>

    {{-- Record User Transaksi Stok Keluar --}}
    <div class="flex flex-col rounded-2xl border border-border overflow-hidden shadow-sm">
        <div class="p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg">Aktivitas Terbaru</h3>
                <p class="text-sm text-secondary">Riwayat transaksi user</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-sm">
                <!-- HEADER -->
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="p-4 pl-6 text-left font-semibold text-secondary">Waktu</th>
                        <th class="p-4 text-left font-semibold text-secondary">User</th>
                        <th class="p-4 text-left font-semibold text-secondary">Aktivitas</th>
                        <th class="p-4 text-left font-semibold text-secondary">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border" id="activityTable" data-url="{{ url()->current() }}">
                    @include('admin.dashboard.activity-table')
                </tbody>
            </table>
        </div>
    </div>

    @push('css')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    @push('js')
        <script>
            // Data dari Laravel (pastikan format benar)
            const attendanceLabels = @json($attendanceLabels ?? []);
            const attendanceData = @json($attendanceData ?? []);
            const productionData = @json($productionData ?? [0, 0, 0]);

            function initializeCharts() {
                // --- Line Chart (Attendance) ---
                const attEl = document.getElementById('attendanceChart');
                if (attEl) {
                    // Pastikan attendanceData adalah array
                    let safeAttendanceData = Array.isArray(attendanceData) ?
                        attendanceData :
                        (typeof attendanceData === 'object' && attendanceData !== null ?
                            Object.values(attendanceData) : []);

                    // Jika masih kosong, isi default 7 nol
                    if (safeAttendanceData.length === 0) {
                        safeAttendanceData = new Array(7).fill(0);
                    }

                    new Chart(attEl, {
                        type: 'line',
                        data: {
                            labels: Array.isArray(attendanceLabels) ? attendanceLabels : new Array(7).fill('?'),
                            datasets: [{
                                label: 'Transaksi',
                                data: safeAttendanceData,
                                borderColor: '#165DFF',
                                backgroundColor: 'rgba(22, 93, 255, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: safeAttendanceData.length > 0 ?
                                        Math.max(...safeAttendanceData) * 1.1 || 100 : 100,
                                    ticks: {
                                        callback: v => v + '%'
                                    }
                                }
                            }
                        }
                    });
                }

                // --- Doughnut Chart (Production Status) ---
                const prodEl = document.getElementById('productionChart');
                if (prodEl) {
                    new Chart(prodEl, {
                        type: 'doughnut',
                        data: {
                            labels: ['Draft', 'Posted', 'Cancelled'],
                            datasets: [{
                                data: productionData,
                                backgroundColor: ['#6B7280', '#30B22D', '#EF4444'],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => `${ctx.label}: ${ctx.parsed}%`
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Jalankan setelah DOM siap
            document.addEventListener('DOMContentLoaded', () => {
                initializeCharts(); // chart dulu
                loadActivity(); // baru activity table
                startAutoRefresh();
            });

            let lastHTML = '';

            function loadActivity() {
                const table = document.getElementById('activityTable');

                // 🔥 stop kalau tidak ada table
                if (!table) return;

                const url = table.dataset.url;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.html) return;

                        // 🔥 update hanya jika berubah
                        if (lastHTML !== data.html) {

                            table.innerHTML = data.html;

                            animateRows();

                            lastHTML = data.html;
                        }

                        // 🔥 re-init lucide icon
                        if (window.lucide) {
                            lucide.createIcons();
                        }
                    })
                    .catch(err => console.error('Auto refresh error:', err));
            }

            /* ==============================
               ANIMASI
            ============================== */
            function animateRows() {
                const rows = document.querySelectorAll('#activityTable tr');

                rows.forEach((row, index) => {
                    row.style.opacity = 0;
                    row.style.transform = 'translateY(10px)';

                    setTimeout(() => {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = 1;
                        row.style.transform = 'translateY(0)';
                    }, index * 40);
                });
            }

            /* ==============================
               AUTO REFRESH (SMART)
            ============================== */
            let interval = null;

            function startAutoRefresh() {
                if (interval) clearInterval(interval);

                interval = setInterval(() => {
                    loadActivity();
                }, 10000);
            }

            /* ==============================
               STOP SAAT TAB TIDAK AKTIF
            ============================== */
            document.addEventListener("visibilitychange", function() {
                if (document.hidden) {
                    clearInterval(interval);
                } else {
                    startAutoRefresh();
                }
            });
        </script>
    @endpush

</x-view.layout.app>
