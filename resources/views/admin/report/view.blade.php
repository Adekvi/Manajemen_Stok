<x-view.layout.app title="Report Data">

    <div id="view-reports" class="page-section">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Reports</span>
        </div>

        <!-- Table Section Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="font-bold text-lg text-foreground">Laporan Bulanan</h3>
                <p class="text-secondary text-sm">Ringkasan keuangan per bulan</p>
            </div>

            <form method="GET" id="filterForm" action="{{ route('admin.master.menu.report') }}">
                <div class="flex gap-3">

                    <select name="tahun"
                        class="border border-border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary/20 text-sm font-medium">
                        @foreach ($listTahun as $th)
                            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                Tahun {{ $th }}
                            </option>
                        @endforeach
                    </select>

                    <!-- 🔥 BUTTON EXPORT -->
                    <button formaction="{{ route('admin.report.exportBulanan') }}" formmethod="GET"
                        class="bg-primary hover:bg-primary-hover text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 shadow-lg shadow-primary/20 transition-all cursor-pointer">

                        <i data-lucide="download" class="size-5"></i>
                        <span class="hidden sm:inline">Export Laporan</span>

                    </button>

                </div>
            </form>
        </div>

        <!-- Table -->
        <div class=" rounded-2xl border border-border overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/50 border-b border-border text-center">
                        <tr>
                            <th rowspan="2" class="p-4 text-sm text-secondary">Bulan</th>

                            <th colspan="2" class="p-4 text-sm text-secondary">
                                Total Transaksi
                            </th>

                            <th rowspan="2" class="p-4 text-sm text-secondary">Total Stok</th>

                            <th colspan="2" class="p-4 text-sm text-secondary">
                                Total Stok
                            </th>
                            <th rowspan="2" class="p-4 text-sm text-secondary">Total Uang</th>
                            <th rowspan="2" class="p-4 text-sm text-secondary">Status</th>
                        </tr>

                        <tr>
                            <th class="p-3 text-xs text-success">Transaksi Masuk</th>
                            <th class="p-3 text-xs text-error">Transaksi Keluar</th>
                            <th class="p-3 text-sm text-secondary">Stok Masuk</th>
                            <th class="p-3 text-sm text-secondary">Stok Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $row)
                            <tr class="border-b border-border hover:bg-muted/30 transition">

                                <!-- BULAN -->
                                <td class="p-4 font-semibold text-foreground">
                                    {{ \Carbon\Carbon::parse($row->bulan . '-01')->translatedFormat('F Y') }}
                                </td>

                                <!-- MASUK -->
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 rounded-lg bg-success/10 text-success text-sm font-semibold">
                                        {{ $row->total_transaksi_masuk }}
                                    </span>
                                </td>

                                <!-- KELUAR -->
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 rounded-lg bg-error/10 text-error text-sm font-semibold">
                                        {{ $row->total_transaksi_keluar }}
                                    </span>
                                </td>

                                <td class="p-4 text-center align-middle text-primary font-semibold">
                                    {{ number_format($totalStok) }}
                                </td>

                                <td class="p-4 text-center align-middle text-success font-medium">
                                    +{{ number_format($row->total_masuk) }}
                                </td>

                                <td class="p-4 text-center align-middle text-error font-medium">
                                    -{{ number_format($row->total_keluar) }}
                                </td>

                                <!-- TOTAL UANG -->
                                <td class="p-4 font-bold">
                                    @if ($row->total_uang < 0)
                                        <span class="text-error">
                                            Rp {{ number_format($row->total_uang, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-success">
                                            Rp {{ number_format($row->total_uang, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>

                                <!-- STATUS -->
                                <td class="p-4">
                                    @if ($row->total_keluar > $row->total_masuk)
                                        <span
                                            class="bg-error/10 text-error px-3 py-1 rounded-full text-xs font-semibold">
                                            Defisit
                                        </span>
                                    @else
                                        <span
                                            class="bg-success/10 text-success px-3 py-1 rounded-full text-xs font-semibold">
                                            Stabil
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-6 text-center text-secondary">
                                    Tidak ada data report
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="flex flex-col gap-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Report Stok</h3>
                    <p class="text-secondary text-sm">Ringkasan Stok Realtime</p>
                </div>
                <form method="GET" id="filterForm">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.report.export', request()->query()) }}"
                            class="bg-primary hover:bg-primary-hover text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 shadow-lg shadow-primary/20 transition-all cursor-pointer">

                            <i data-lucide="download" class="size-5"></i>
                            <span class="hidden sm:inline">Export</span>
                        </a>
                    </div>
                </form>
            </div>
            <div class="border border-border rounded-2xl shadow-sm flex flex-col">
                <div class="p-5 border-b border-border flex flex-col gap-4">

                    <!-- TOP BAR -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                        <!-- SEARCH -->
                        <div class="relative w-full sm:w-80 group">
                            <i data-lucide="search"
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary group-focus-within:text-primary transition">
                            </i>

                            <input type="text" id="search-kartu" placeholder="Cari stok barang..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border bg-muted/40 text-smfocus:ring-2 focus:ring-primary/20 focus:border-primaryoutline-none transition-all">
                        </div>

                        <!-- ACTION -->
                        <div class="flex items-center gap-2">

                            <button onclick="openFilterModal()"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium border border-border bg-white hover:bg-primary hover:text-white hover:border-primary shadow-sm hover:shadow-md transition-all duration-200 active:scale-95">

                                <i data-lucide="filter" class="size-4"></i>
                                <span>Filter</span>
                            </button>

                        </div>
                    </div>

                    <!-- FILTER INFO -->
                    <div id="filterInfoWrapper" class="hidden flex justify-start">

                        <div id="filterInfo"
                            class="flex items-center gap-3 px-4 py-2 rounded-full bg-primary/10 text-primary text-xs font-medium border border-primary/20 backdrop-blur-smanimate-fadeIn">

                            <i data-lucide="calendar" class="size-4"></i>
                            <span id="filterText">
                                Filter aktif
                            </span>
                            <!-- divider -->
                            <span class="w-px h-4 bg-primary/30"></span>
                            <!-- reset -->
                            <button onclick="clearFilter()"
                                class="flex items-center gap-1 text-red-500 hover:text-red-600 transition">

                                <i data-lucide="rotate-ccw" class="size-4"></i>
                                <span class="text-xs">Reset</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-muted/50 border-b border-border">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-secondary">No</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Nama</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Produk</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Tanggal</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Tipe</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Jumlah</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Stok Sebelum</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Stok Setelah</th>
                                {{-- <th class="px-6 py-4 font-semibold text-secondary text-right">Status</th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border" id="table-kartu">
                            @include('admin.report.table')
                        </tbody>
                    </table>
                </div>

                <div
                    class="p-4 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-secondary">
                    <p>
                        Menampilkan {{ $kartu->firstItem() }} - {{ $kartu->lastItem() }}
                        dari {{ $kartu->total() }} produk
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="flex gap-2">
                            {{-- tombol previous --}}
                            @if ($kartu->onFirstPage())
                                <span
                                    class="size-9 flex items-center justify-center rounded-lg border border-border opacity-50">
                                    <i data-lucide="chevron-left" class="size-4"></i>
                                </span>
                            @else
                                <a href="{{ $kartu->previousPageUrl() }}"
                                    class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                    <i data-lucide="chevron-left" class="size-4"></i>
                                </a>
                            @endif
                            {{-- nomor halaman --}}
                            @for ($i = 1; $i <= $kartu->lastPage(); $i++)
                                @if ($i == $kartu->currentPage())
                                    <span
                                        class="size-9 flex items-center justify-center rounded-lg bg-primary text-white shadow-md shadow-primary/20 cursor-pointer">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $kartu->url($i) }}"
                                        class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            {{-- tombol next --}}
                            @if ($kartu->hasMorePages())
                                <a href="{{ $kartu->nextPageUrl() }}"
                                    class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                    <i data-lucide="chevron-right" class="size-4"></i>
                                </a>
                            @else
                                <span
                                    class="size-9 flex items-center justify-center rounded-lg border border-border opacity-50">
                                    <i data-lucide="chevron-right" class="size-4"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="p-6 rounded-2xl border border-border shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Analitik Stok</h3>
                    <p class="text-secondary text-sm">Grafik pemasukan dalam 6 bulan terakhir</p>
                </div>
                <button class="p-2 hover:bg-muted rounded-lg transition-colors"><i data-lucide="more-horizontal"
                        class="size-5 text-secondary"></i></button>
            </div>
            <div class="h-[320px] w-full">
                <canvas id="reportsIncomeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- MODAL FILTER --}}
    <div id="modalFilter"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm p-4 transition-all duration-300 opacity-0">
        <div id="modalFilterStyle"
            class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-border transform transition-all duration-300 scale-95 opacity-0">
            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-border">
                <h2 class="text-base font-semibold flex items-center gap-2 text-gray-800">
                    <i data-lucide="filter" class="size-5 text-primary"></i>
                    Filter Laporan
                </h2>
                <button onclick="closeFilterModal()" class="p-2 rounded-xl hover:bg-gray-100 transition">
                    <i data-lucide="x" class="size-5 text-gray-500"></i>
                </button>
            </div>
            <!-- BODY -->
            <div class="p-6 space-y-6">
                <!-- QUICK FILTER -->
                <div>
                    <label class="text-sm font-medium text-secondary mb-3 block">
                        Filter Cepat
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="quickFilterGroup">
                        @foreach ([
        'hari' => 'Hari Ini',
        'minggu' => 'Mingguan',
        'bulan' => 'Bulanan',
        '3bulan' => '3 Bulan',
        '6bulan' => '6 Bulan',
        'tahun' => '1 Tahun',
    ] as $key => $label)
                            <button type="button" data-type="{{ $key }}"
                                onclick="setQuickFilter('{{ $key }}')" class="filter-chip group">

                                <span>{{ $label }}</span>

                                <i data-lucide="check"
                                    class="size-4 opacity-0 group-[.active]:opacity-100 transition"></i>

                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600 mb-3 block">
                        Atur Tanggal Manual
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-500">Dari</span>
                            <input type="date" id="start_date" class="input-date">
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-500">Sampai</span>
                            <input type="date" id="end_date" class="input-date">
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-border">

                <button onclick="resetFilter()" class="text-sm text-gray-500 hover:text-primary transition">
                    Reset
                </button>

                <div class="flex gap-2">
                    <button onclick="closeFilterModal()"
                        class="px-4 py-2 text-sm rounded-xl border border-border hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button onclick="applyFilter()"
                        class="px-5 py-2 text-sm rounded-xl bg-primary text-white font-medium hover:bg-primary-hover transition">
                        Terapkan
                    </button>
                </div>
            </div>

        </div>
    </div>

    @push('css')
        <style>
            .filter-chip {
                @apply flex items-center justify-between gap-2 px-4 py-3 rounded-xl text-sm font-medium border border-border bg-white hover:border-primary hover:bg-primary/5 transition-all duration-200 cursor-pointer;

                transform: scale(1);
            }

            .filter-chip:hover {
                transform: scale(1.03);
            }

            .filter-chip.active {
                @apply bg-primary text-white border-primary shadow-lg shadow-primary/20;
            }

            .filter-chip.active:hover {
                transform: scale(1.05);
            }

            /* input date lebih clean */
            input[type="date"] {
                @apply px-4 py-3 border border-border rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all;
            }

            /* tombol footer */
            .btn-primary {
                @apply px-5 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary-hover shadow-md hover:shadow-lg transition-all;
            }

            .btn-secondary {
                @apply px-4 py-2 border border-border rounded-xl text-sm hover:bg-muted transition-all;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.25s ease-out;
            }
        </style>
    @endpush

    @push('js')
        <script src="{{ asset('asset/js/report/filter.js') }}"></script>
        <script>
            function switchView(view) {
                // Sembunyikan semua section
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                // Mapping view → id yang sebenarnya
                const viewMap = {
                    'list': 'view-reports',
                    'order': 'view-order-list'
                };

                const targetId = viewMap[view] || 'view-reports'; // fallback ke list
                const target = document.getElementById(targetId);

                if (target) {
                    target.classList.remove('hidden');
                }
            }

            // Default tampilan saat halaman dibuka
            document.addEventListener('DOMContentLoaded', () => {
                switchView('dashboard'); // ← pakai 'list' bukan 'stok-list'
                lucide.createIcons();
            });

            // Filter
            document.addEventListener('DOMContentLoaded', function() {
                const selectTahun = document.querySelector('select[name="tahun"]');

                if (selectTahun) {
                    selectTahun.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                }
            });

            // LOAD OTOMATIS
            document.addEventListener('DOMContentLoaded', () => {

                let isTyping = false;
                let lastId = null;
                let debounceTimer;

                const searchInput = document.getElementById('search-kartu');

                /* ==============================
                   LOAD DATA (AJAX)
                ============================== */
                function loadKartu(page = null) {

                    const url = new URL(window.location.href);

                    // ambil search
                    if (searchInput && searchInput.value) {
                        url.searchParams.set('search', searchInput.value);
                    }

                    // pagination
                    if (page) {
                        url.searchParams.set('page', page);
                    }

                    fetch(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {

                            // 🔥 OPTIMASI: cek apakah data berubah
                            if (lastId && data.last_id && lastId === data.last_id) {
                                return; // tidak ada data baru
                            }

                            lastId = data.last_id;

                            const tbody = document.getElementById('table-kartu');

                            if (data.empty) {
                                tbody.innerHTML = `
                                    <tr>
                                        <td colspan="8" class="p-6 text-center text-secondary">
                                            Data tidak ditemukan
                                        </td>
                                    </tr>
                                `;
                            } else {
                                tbody.innerHTML = data.html;
                            }

                            // re-init icon
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }

                        })
                        .catch(err => console.error('Auto reload error:', err));
                }

                /* ==============================
                   AUTO REFRESH (10 DETIK)
                ============================== */
                setInterval(() => {
                    if (!isTyping) {
                        loadKartu();
                    }
                }, 10000);

                /* ==============================
                   SEARCH (DEBOUNCE)
                ============================== */
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {

                        isTyping = true;

                        clearTimeout(debounceTimer);

                        debounceTimer = setTimeout(() => {
                            loadKartu();
                            isTyping = false;
                        }, 500);

                    });
                }

                /* ==============================
                   PAGINATION AJAX
                ============================== */
                document.addEventListener('click', function(e) {

                    const link = e.target.closest('a[href*="page="]');

                    if (link) {
                        e.preventDefault();

                        const url = new URL(link.href);
                        const page = url.searchParams.get('page');

                        loadKartu(page);
                    }

                });

                /* ==============================
                   INIT LOAD
                ============================== */
                loadKartu();

            });

            const chartLabels = @json($labels);
            const chartData = @json($data);

            const ctxReportsIncome = document.getElementById('reportsIncomeChart')?.getContext('2d');

            if (ctxReportsIncome) {
                new Chart(ctxReportsIncome, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Pemasukan',
                            data: chartData,
                            borderColor: '#165DFF',
                            backgroundColor: 'rgba(22, 93, 255, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F3'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-view.layout.app>
