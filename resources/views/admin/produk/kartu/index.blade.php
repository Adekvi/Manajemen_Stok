<x-view.layout.app title="Kartu Stok">

    <div id="view-order-list" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.master.menu.stokmasuk') }}" onclick="switchView('order-masuk')"
                class="hover:text-primary transition-colors">Stok Masuk</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <a href="{{ route('admin.master.menu.stokkeluar') }}" onclick="switchView('order-keluar')"
                class="hover:text-primary transition-colors">Stok Keluar</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Kartu Stok</span>
        </div>
        <div class="flex flex-col gap-6 mb-10">
            <div>
                <h3 class="font-bold text-lg text-foreground">Manajemen Stok</h3>
                <p class="text-secondary text-sm">Kartu Stok Realtime</p>
            </div>
            <div class="border border-border rounded-2xl shadow-sm flex flex-col">
                <div class="p-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="relative w-full sm:w-72">
                        <i data-lucide="search"
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                        <input type="text" id="search-kartu" placeholder="Cari Stok..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-border bg-muted/30 focus:ring-1 focus:ring-primary outline-none text-sm transition-all">
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            class="flex items-center gap-2 px-4 py-2.5 border border-border rounded-xl text-sm font-medium hover:bg-muted transition-colors cursor-pointer">
                            <i data-lucide="filter" class="size-4 text-secondary"></i>
                            Filter
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-muted/50 border-b border-border">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-secondary">No</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Produk</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Tanggal</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Tipe</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Jumlah</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Stok Sebelum</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Stok Setelah</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Keterangan</th>
                                <th class="px-6 py-4 font-semibold text-secondary text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border" id="table-kartu">
                            @include('admin.produk.kartu.table')
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
    </div>

    <div id="view-stok-detail" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')" class="hover:text-primary transition-colors">Kartu
                    Stok</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <span class="font-medium text-foreground">Detail Produk</span>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <div class="w-full lg:w-[360px] flex flex-col gap-6">
                    <div class=" border border-border rounded-2xl shadow-sm p-6 flex flex-col items-center">
                        <div class="w-full aspect-square bg-muted rounded-xl mb-6 overflow-hidden relative group">
                            <img id="detail-image"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button
                                    class="size-10 flex items-center justify-center  rounded-xl hover:bg-gray-100 cursor-pointer shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300"><i
                                        data-lucide="eye" class="size-5 text-gray-700"></i></button>
                            </div>
                        </div>
                        <h3 id="detail-nama" class="font-bold text-xl text-center mb-1 text-foreground"></h3>
                        <p id="detail-kode" class="text-secondary text-sm mb-5"></p>
                        <div class="w-full border-t border-border pt-5 flex justify-between items-center">
                            <span class="text-sm font-medium text-secondary">Status Produk</span>
                            <span id="detail-status-text" class="px-3 py-1.5 rounded-full text-xs font-bold"></span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 flex flex-col gap-6">
                    {{-- INFORMASI DETAIL --}}
                    <div class="border border-border rounded-2xl shadow-sm p-6 md:p-8">
                        {{-- HEADER --}}
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-border">
                            <div>
                                <h3 class="font-bold text-lg text-foreground">Informasi Detail</h3>
                                <p class="text-sm text-secondary mt-1">
                                    Spesifikasi dan informasi transaksi produk
                                </p>
                            </div>
                        </div>
                        {{-- GRID DATA --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kode Transaksi --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Kode Transaksi
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <i data-lucide="receipt" class="size-4 text-primary"></i>
                                    <span id="detail-kode-transaksi"
                                        class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            {{-- Tanggal --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Tanggal
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <i data-lucide="calendar" class="size-4 text-primary"></i>
                                    <span id="detail-tanggal" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            {{-- Jumlah --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Jumlah Produk Masuk
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <i data-lucide="package" class="size-4 text-primary"></i>
                                    <span id="detail-jumlah" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            {{-- Status --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Status
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <i data-lucide="info" class="size-4 text-primary"></i>
                                    <span id="detail-status" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            {{-- Kategori --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Kategori Produk
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <i data-lucide="tag" class="size-4 text-primary"></i>
                                    <span id="detail-kategori" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            {{-- Harga --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Harga Satuan
                                </p>
                                <div class="flex items-center gap-2 p-3.5 border border-border rounded-xl">
                                    <span id="detail-harga" class="font-bold text-primary"></span>
                                    <span class="text-xs text-secondary">/</span>
                                    <span id="detail-satuan" class="text-xs text-secondary"></span>
                                </div>
                            </div>
                        </div>
                        {{-- DESKRIPSI --}}
                        <div class="mt-6">
                            <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                Deskripsi Produk
                            </p>

                            <div id="detail-deskrip"
                                class="p-5 border border-border font-semibold rounded-xl text-sm text-foreground leading-relaxed">
                            </div>
                        </div>
                    </div>

                    {{-- PERFORMA PRODUK --}}
                    <div class="border border-border rounded-2xl shadow-sm p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-lg text-foreground">Performa Produk</h3>
                                <p class="text-sm text-secondary mt-1">
                                    Informasi stok produk saat ini
                                </p>
                            </div>
                            <span
                                class="text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20">
                                Auto-deduct
                            </span>
                        </div>
                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-4 p-4 border border-border rounded-xl hover:bg-muted/30 transition-colors group">
                                <div
                                    class="size-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                    <i data-lucide="disc" class="size-6 text-primary"></i>
                                </div>
                                <div class="text-right">
                                    <p id="detail-stok" class="font-bold text-sm text-foreground"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            function switchView(view) {

                document.querySelectorAll('.view-section')
                    .forEach(el => el.classList.add('hidden'));

                const viewMap = {
                    'list': 'view-order-list',
                    'detail': 'view-stok-detail'
                };

                const target = document.getElementById(viewMap[view]);

                if (target) {
                    target.classList.remove('hidden');
                }

                lucide.createIcons();
            }

            /* =========================================================
                   LOADING STATE
                ========================================================= */

            function loadingState() {

                return `
                <tr>
                    <td colspan="9" class="py-14 text-center">
                        <div class="flex items-center justify-center gap-2 text-secondary">

                            <svg class="animate-spin size-5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-dasharray="60"
                                    stroke-dashoffset="20">
                                </circle>
                            </svg>

                            <span>Mencari data...</span>

                        </div>
                    </td>
                </tr>
                `;
            }


            /* =========================================================
               EMPTY STATE
            ========================================================= */

            function emptyState() {

                return `
                <tr>
                    <td colspan="9" class="py-14 text-center">

                        <div class="flex flex-col items-center gap-3 text-secondary">

                            <div class="size-12 rounded-xl bg-muted flex items-center justify-center">
                                <i data-lucide="search-x" class="size-6"></i>
                            </div>

                            <div>
                                <p class="font-semibold text-foreground">
                                    Data tidak ditemukan
                                </p>
                                <p class="text-sm text-secondary">
                                    Coba gunakan kata kunci lain
                                </p>
                            </div>

                        </div>

                    </td>
                </tr>
                `;
            }


            /* =========================================================
               AJAX SEARCH
            ========================================================= */

            const searchInput = document.getElementById('search-kartu');
            const tableBody = document.getElementById('table-kartu');

            let timer = null;

            if (searchInput) {

                searchInput.addEventListener('keyup', function() {

                    clearTimeout(timer);

                    const keyword = this.value;

                    timer = setTimeout(() => {

                        tableBody.innerHTML = loadingState();

                        fetch(`{{ route('admin.master.menu.kartustok') }}?search=${keyword}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {

                                if (data.empty) {

                                    tableBody.innerHTML = emptyState();

                                } else {

                                    tableBody.innerHTML = data.html;

                                }

                                lucide.createIcons();

                            })
                            .catch(err => {

                                console.error(err);

                                tableBody.innerHTML = `
                                <tr>
                                    <td colspan="9" class="text-center py-10 text-red-500">
                                        Terjadi kesalahan saat memuat data
                                    </td>
                                </tr>
                                `;

                            });

                    }, 400);

                });

            }

            /* ===============================
               LOAD DETAIL KARTU STOK
            =============================== */

            async function openDetailKartu(id) {

                try {

                    const res = await fetch(`/admin/master/menu/kartu-detail/${id}`);

                    if (!res.ok) {
                        throw new Error("Gagal mengambil data");
                    }

                    const data = await res.json();

                    const produk = data.produk;

                    /* ===============================
                       INFORMASI PRODUK
                    =============================== */

                    document.getElementById('detail-nama').innerText =
                        produk.nama_produk;

                    document.getElementById('detail-kode').innerText =
                        produk.kode_produk;

                    const img = document.getElementById('detail-image');

                    if (produk.foto_produk) {
                        img.src = `/produk/${produk.foto_produk}`;
                    } else {
                        img.src = `/asset/image/no-image.jpg`;
                    }

                    /* ===============================
                       STATUS PRODUK
                    =============================== */

                    const statusEl = document.getElementById('detail-status-text');

                    if (produk.status === 'aktif') {

                        statusEl.innerText = 'Aktif';
                        statusEl.className =
                            "px-3 py-1.5 rounded-full text-xs font-bold bg-success/10 text-success";

                    } else {

                        statusEl.innerText = 'Nonaktif';
                        statusEl.className =
                            "px-3 py-1.5 rounded-full text-xs font-bold bg-error/10 text-error";

                    }

                    /* ===============================
                       DATA TRANSAKSI
                    =============================== */

                    document.getElementById('detail-kode-transaksi').innerText =
                        data.kode_transaksi;

                    document.getElementById('detail-tanggal').innerText =
                        new Date(data.tanggal).toLocaleDateString('id-ID');

                    document.getElementById('detail-jumlah').innerText =
                        `${data.qty} ${produk.satuan}`;

                    document.getElementById('detail-status').innerText =
                        data.tipe.replace('_', ' ').toUpperCase();

                    document.getElementById('detail-kategori').innerText =
                        produk.kategori;

                    document.getElementById('detail-harga').innerText =
                        'Rp ' + Number(produk.harga).toLocaleString('id-ID');

                    document.getElementById('detail-satuan').innerText =
                        produk.satuan;

                    document.getElementById('detail-deskrip').innerText =
                        produk.keterangan ?? '-';

                    /* ===============================
                       STOK SAAT INI
                    =============================== */

                    const stokEl = document.getElementById('detail-stok');
                    const stok = produk.stok;

                    if (stok === 0) {
                        stokEl.innerHTML = `
                            <span class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                ${stok} - Stok habis
                            </span>
                        `;
                    } else if (stok < 10) {
                        stokEl.innerHTML = `
                            <span class="flex items-center gap-2 text-orange-600 dark:text-orange-400">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                ${stok} - Stok hampir habis
                            </span>
                        `;
                    } else {
                        stokEl.innerHTML = `
                            <span class="flex items-center gap-2 text-green-600 dark:text-green-400">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                ${stok} - Stok aman
                            </span>
                        `;
                    }

                    lucide.createIcons();


                    /* ===============================
                       PINDAH KE VIEW DETAIL
                    =============================== */

                    switchView('detail');

                } catch (error) {

                    console.error(error);
                    alert("Gagal memuat detail kartu stok");

                }

            }


            /* ===============================
               INIT PAGE
            =============================== */

            document.addEventListener('DOMContentLoaded', () => {

                switchView('list');

                lucide.createIcons();

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
                                        <td colspan="9" class="p-6 text-center text-secondary">
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
        </script>
    @endpush

</x-view.layout.app>
