<x-view.layout.app title="Data Produk">

    <div id="view-stok-list" class="view-section hidden flex flex-col flex-1 h-full">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('user.dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Data Produk</span>
        </div>

        <div class="flex flex-col gap-6 mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="relative w-full md:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-lg">Kelola Produk</span>
                    </div>
                    <p class="text-sm text-secondary">Total {{ $produk->total() }} produk</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <div class="relative flex-1 max-w-md">
                        <i data-lucide="search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                        <input type="text" id="produkSearch" placeholder="Cari produk..."
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:border-primary outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class=" border border-border rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-muted/50 border-b border-border">
                            <tr>
                                <th class="p-4 pl-6 font-semibold text-secondary">No</th>
                                <th class="p-4 pl-6 font-semibold text-secondary">Nama Produk</th>
                                <th class="p-4 font-semibold text-secondary">Stok</th>
                                <th class="p-4 font-semibold text-secondary">Harga Satuan</th>
                                <th class="p-4 font-semibold text-secondary">Status</th>
                                <th class="p-4 text-center font-semibold text-secondary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border" id="stokTable">
                            @include('user.produk.table')
                        </tbody>
                    </table>
                </div>
                <div
                    class="p-4 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-secondary">
                    <p>
                        Menampilkan {{ $produk->firstItem() }} - {{ $produk->lastItem() }}
                        dari {{ $produk->total() }} produk
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="flex gap-2">
                            {{-- tombol previous --}}
                            @if ($produk->onFirstPage())
                                <span
                                    class="size-9 flex items-center justify-center rounded-lg border border-border opacity-50">
                                    <i data-lucide="chevron-left" class="size-4"></i>
                                </span>
                            @else
                                <a href="{{ $produk->previousPageUrl() }}"
                                    class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                    <i data-lucide="chevron-left" class="size-4"></i>
                                </a>
                            @endif
                            {{-- nomor halaman --}}
                            @for ($i = 1; $i <= $produk->lastPage(); $i++)
                                @if ($i == $produk->currentPage())
                                    <span
                                        class="size-9 flex items-center justify-center rounded-lg bg-primary text-white shadow-md shadow-primary/20 cursor-pointer">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $produk->url($i) }}"
                                        class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            {{-- tombol next --}}
                            @if ($produk->hasMorePages())
                                <a href="{{ $produk->nextPageUrl() }}"
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
                <a href="{{ route('user.dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')" class="hover:text-primary transition-colors">Produk</a>
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
                    <div class=" border border-border rounded-2xl shadow-sm p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-border">
                            <div>
                                <h3 class="font-bold text-lg text-foreground">Informasi Detail</h3>
                                <p class="text-sm text-secondary mt-1">Spesifikasi dan harga produk saat ini.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Kategori Produk</p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <i data-lucide="tag" class="size-4 text-primary"></i>
                                    <span id="detail-kategori" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">Harga
                                    Satuan</p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <span id="detail-harga" class="font-bold text-primary"></span>
                                    <span class="text-xs text-secondary font-medium">/</span>
                                    <span id="detail-satuan" class="text-xs text-secondary font-medium"></span>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Deskripsi Produk</p>
                                <div id="detail-des"
                                    class="p-5 border border-border font-semibold rounded-xl text-sm text-foreground leading-relaxed">
                                    <p class="mb-3"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border border-border rounded-2xl shadow-sm p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-lg text-foreground">Performa Produk</h3>
                                <p class="text-sm text-secondary mt-1">Stok akan bertambah otomatis.
                                </p>
                            </div>
                            <span
                                class="text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20">Auto-deduct</span>
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
                                    <p class="text-xs text-success font-medium"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .view-section {
                transition: opacity 0.25s ease, transform 0.25s ease;
                opacity: 0;
                transform: translateY(8px);
            }

            .view-section:not(.hidden) {
                opacity: 1;
                transform: translateY(0);
            }

            #upload-area img {
                transition: transform .3s ease;
            }

            /* Foto */
            #upload-area:hover img {
                transform: scale(1.05);
            }

            #logo-preview {
                transition: transform 0.2s ease;
            }
        </style>
    @endpush

    @push('js')
        <script>
            let currentProdukId = null;

            // --------------------------------------------------
            // 1. Utility / Fungsi Umum
            // --------------------------------------------------
            function switchView(view) {
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                const viewMap = {
                    list: 'view-stok-list',
                    detail: 'view-stok-detail'
                };

                const targetId = viewMap[view] || 'view-stok-list';
                const target = document.getElementById(targetId);
                if (target) target.classList.remove('hidden');

                lucide.createIcons();
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka || 0);
            }

            function parseNumber(str) {
                return parseInt(str.replace(/[^0-9]/g, '')) || 0;
            }

            // --------------------------------------------------
            // 2. Inisialisasi Global (DOMContentLoaded)
            // --------------------------------------------------
            document.addEventListener('DOMContentLoaded', () => {
                // Default tampilkan list
                switchView('list');

                // Refresh lucide icons
                lucide.createIcons();

                // Event listener search (debounce)
                const searchInput = document.getElementById('produkSearch');

                if (searchInput) {
                    let typingTimer;
                    const delay = 500;
                    searchInput.addEventListener('input', () => {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(() => {
                            loadData(1);
                        }, delay);
                    });
                }
            });

            // --------------------------------------------------
            // 3. List / Table View
            // --------------------------------------------------
            function loadData(page = 1) {

                const search = document.getElementById('produkSearch')?.value || '';

                fetch(`?page=${page}&search=${encodeURIComponent(search)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        const tableBody = document.getElementById('stokTable');

                        if (!tableBody) return;
                        if (data.empty === true) {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-10 text-secondary">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="search-x" class="w-8 h-8"></i>
                                        <span>Data tidak ditemukan</span>
                                    </div>
                                </td>
                            </tr>
                            `;
                        } else if (data.html) {
                            tableBody.innerHTML = data.html;
                        } else {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-10 text-secondary">
                                    <span>Terjadi kesalahan memuat data</span>
                                </td>
                            </tr>
                            `;
                        }
                        lucide.createIcons();
                    })

                    .catch(err => {
                        console.error('Load data error:', err);
                    });
            }

            // Handle pagination click
            document.addEventListener('click', e => {
                const link = e.target.closest('.pagination a');
                if (!link) return;
                e.preventDefault();

                const url = new URL(link.href);
                const page = url.searchParams.get('page');
                if (page) loadData(page);
            });

            function showDetail(id) {

                currentProdukId = id;

                fetch(`/user/produk-view/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        const statusEl = document.getElementById("detail-status-text");
                        const img = document.getElementById("detail-image");
                        let stokEl = document.getElementById("detail-stok");

                        document.getElementById("detail-nama").innerText = data.nama_produk;
                        document.getElementById("detail-kode").innerText = "Kode: " + data.kode_produk;
                        document.getElementById("detail-kategori").innerText = data.kategori ?? "-";
                        document.getElementById("detail-satuan").innerText = data.satuan;

                        if (data.stok < 0) {
                            stokEl.innerHTML = `
                                <span class="flex items-center gap-2 text-red-700 dark:text-red-500 font-semibold">
                                    <i data-lucide="alert-octagon" class="w-4 h-4"></i>
                                    ${data.stok} - Stok minus
                                </span>
                            `;
                        } else if (data.stok === 0) {
                            stokEl.innerHTML = `
                                <span class="flex items-center gap-2 text-red-600">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    ${data.stok} - Stok habis
                                </span>
                            `;
                        } else if (data.stok < 10) {
                            stokEl.innerHTML = `
                                <span class="flex items-center gap-2 text-orange-600">
                                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    ${data.stok} - Stok hampir habis
                                </span>
                            `;
                        } else {
                            stokEl.innerHTML = `
                                <span class="flex items-center gap-2 text-green-600">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    ${data.stok} - Stok aman
                                </span>
                            `;
                        }

                        lucide.createIcons();

                        document.getElementById("detail-harga").innerText =
                            "Rp " + Number(data.harga || 0).toLocaleString('id-ID');

                        document.getElementById("detail-des").innerText =
                            data.keterangan ?? "-";

                        if (data.foto_produk) {
                            img.src = "/produk/" + data.foto_produk;
                        } else {
                            img.src = "/asset/image/no-image.jpg";
                        }

                        statusEl.innerText =
                            data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        if (data.status === 'aktif') {
                            statusEl.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-success/10 text-success";
                        } else if (data.status === 'nonaktif') {
                            statusEl.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-error/10 text-error";
                        }

                        switchView('detail');
                    })
                    .catch(err => console.error(err));
            }

            // AUTO RELOAD
            let lastHash = null;
            let interval = null;

            function loadProduk() {
                const table = document.getElementById('stokTable');
                if (!table) return;

                const url = new URL(window.location.href);

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.html) return;

                        // 🔥 CEK PERUBAHAN
                        if (lastHash !== data.hash) {

                            // update isi tabel
                            table.innerHTML = data.html;

                            animateRows();

                            lastHash = data.hash;

                            // re-init icon kalau pakai lucide
                            if (window.lucide) {
                                lucide.createIcons();
                            }
                        }

                    })
                    .catch(err => console.error('Auto reload error:', err));
            }

            /* ==============================
               ANIMASI HALUS
            ============================== */
            function animateRows() {
                const rows = document.querySelectorAll('#stokTable tr');

                rows.forEach((row, index) => {
                    row.style.opacity = 0;
                    row.style.transform = 'translateY(10px)';

                    setTimeout(() => {
                        row.style.transition = 'all 0.25s ease';
                        row.style.opacity = 1;
                        row.style.transform = 'translateY(0)';
                    }, index * 30);
                });
            }

            /* ==============================
               AUTO POLLING
            ============================== */
            function startAutoReload() {
                if (interval) clearInterval(interval);

                interval = setInterval(() => {
                    loadProduk();
                }, 5000); // 🔥 tiap 5 detik (ideal)
            }

            /* ==============================
               STOP SAAT TAB TIDAK AKTIF
            ============================== */
            document.addEventListener("visibilitychange", function() {
                if (document.hidden) {
                    clearInterval(interval);
                } else {
                    startAutoReload();
                }
            });

            /* ==============================
               INIT
            ============================== */
            document.addEventListener('DOMContentLoaded', () => {
                loadProduk(); // first load sync
                startAutoReload(); // start polling
            });
        </script>
    @endpush

</x-view.layout.app>
