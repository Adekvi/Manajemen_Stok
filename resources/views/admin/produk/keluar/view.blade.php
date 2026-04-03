<x-view.layout.app title="Stok Keluar">

    <div id="view-stok-list" class="view-section hidden flex flex-col flex-1 h-full overflow-y-auto">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Stok Keluar</span>
        </div>

        <div class="p-6 flex flex-col gap-6">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="relative w-full md:w-auto">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-lg">Kelola Stok</span>
                        </div>
                        <p class="text-sm text-secondary">Total {{ $keluar->total() }} produk</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <div class="relative flex-1 max-w-md">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                            <input type="text" id="produkSearch" placeholder="Cari produk..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-border placeholder:text-secondary dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-foreground">
                        Pilih Produk <span class="text-error">*</span>
                    </label>

                    <div class="flex gap-3 items-center max-w-xl">
                        <div class="relative flex-1">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                            <input type="text" id="produk_search_input" placeholder="Cari produk atau kode..."
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-border placeholder:text-secondary dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            <!-- tombol clear -->
                            <button type="button" id="clear_produk"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-error hidden">
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                            <div id="produk_dropdown"
                                class="absolute left-0 right-0 top-full mt-1 border border-border rounded-xl shadow-lg dark:bg-neutral-900 backdrop-blur-sm hidden max-h-60 overflow-y-auto z-50">
                            </div>
                        </div>

                        <button type="button" id="btnTambahProduk" disabled
                            class="px-5 py-3 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary-hover transition flex items-center gap-2 disabled:opacity-50">
                            <i data-lucide="plus" class="size-4"></i>
                            Tambah
                        </button>

                    </div>
                </div>
                <div class="border border-border rounded-2xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-muted/50 border-b border-border">
                                <tr>
                                    <th class="p-4 pl-6 font-semibold text-secondary">No</th>
                                    <th class="p-4 pl-6 font-semibold text-secondary">Kode Transaksi</th>
                                    <th class="p-4 pl-6 font-semibold text-secondary">Nama Produk</th>
                                    <th class="p-4 font-semibold text-secondary">Jumlah Produk Keluar</th>
                                    <th class="p-4 font-semibold text-secondary">Harga Satuan</th>
                                    <th class="p-4 font-semibold text-secondary">Status</th>
                                    <th class="p-4 text-center font-semibold text-secondary">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" id="stokTable">
                                @include('admin.produk.keluar.table')
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="p-4 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-secondary">
                        <p>
                            Menampilkan {{ $keluar->firstItem() }} - {{ $keluar->lastItem() }}
                            dari {{ $keluar->total() }} produk
                        </p>
                        <div class="flex items-center gap-2">
                            <div class="flex gap-2">
                                {{-- tombol previous --}}
                                @if ($keluar->onFirstPage())
                                    <span
                                        class="size-9 flex items-center justify-center rounded-lg border border-border opacity-50">
                                        <i data-lucide="chevron-left" class="size-4"></i>
                                    </span>
                                @else
                                    <a href="{{ $keluar->previousPageUrl() }}"
                                        class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                        <i data-lucide="chevron-left" class="size-4"></i>
                                    </a>
                                @endif
                                {{-- nomor halaman --}}
                                @for ($i = 1; $i <= $keluar->lastPage(); $i++)
                                    @if ($i == $keluar->currentPage())
                                        <span
                                            class="size-9 flex items-center justify-center rounded-lg bg-primary text-white shadow-md shadow-primary/20 cursor-pointer">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ $keluar->url($i) }}"
                                            class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor
                                {{-- tombol next --}}
                                @if ($keluar->hasMorePages())
                                    <a href="{{ $keluar->nextPageUrl() }}"
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
                        <!-- BODY -->
                        <tbody class="divide-y divide-border" id="activityTable" data-url="{{ url()->current() }}">
                            @include('admin.produk.keluar.activity')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="view-stok-edit" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')"
                    class="hover:text-primary transition-colors">Produk</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <span class="font-medium text-foreground">Edit Produk</span>
            </div>
            <div class=" border border-border rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-border">
                    <h3 class="font-bold text-lg text-foreground">Informasi Produk</h3>
                    <p class="text-sm text-secondary mt-1">Ubah stok produk.</p>
                </div>
                <form id="editForm" class="p-6 md:p-8 flex flex-col gap-6" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="produk_id" name="produk_id">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="w-full lg:w-[360px] flex flex-col gap-6">
                            <div class=" border border-border rounded-2xl shadow-sm p-6 flex flex-col items-center">
                                <div
                                    class="w-full aspect-square bg-muted rounded-xl mb-6 overflow-hidden relative group">
                                    <img id="data-image"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <button
                                            class="size-10 flex items-center justify-center  rounded-xl hover:bg-gray-100 cursor-pointer shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300"><i
                                                data-lucide="eye" class="size-5 text-gray-700"></i></button>
                                    </div>
                                </div>
                                <h3 id="data-nama" class="font-bold text-xl text-center mb-1 text-foreground"></h3>
                                <p id="data-kode" class="text-secondary text-sm mb-5"></p>
                                <div class="w-full border-t border-border pt-5 flex justify-between items-center">
                                    <span class="text-sm font-medium text-secondary">Status Produk</span>
                                    <span id="data-status-text"
                                        class="px-3 py-1.5 rounded-full text-xs font-bold"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col gap-6">
                            <div class="border border-border rounded-2xl shadow-sm p-6 md:p-8">
                                {{-- HEADER --}}
                                <div class="flex items-center justify-between mb-8 pb-6 border-b border-border">
                                    <div>
                                        <h3 class="font-semibold text-lg text-foreground">
                                            Informasi Detail
                                        </h3>
                                        <p class="text-sm text-secondary mt-1">
                                            Spesifikasi dan harga produk saat ini
                                        </p>
                                    </div>
                                </div>

                                {{-- FORM INPUT --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                                    <div class="flex flex-col gap-2">
                                        <label class="text-xs font-medium text-secondary uppercase tracking-wide">
                                            Kode Transaksi
                                        </label>
                                        <input type="text" id="edit-kode" name="kode_transaksi" readonly
                                            class="w-full h-11 px-4 border border-border rounded-xl bg-muted text-sm">
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="flex flex-col gap-2">
                                        <label class="text-xs font-medium text-secondary uppercase tracking-wide">
                                            Tanggal
                                        </label>
                                        <input type="date" id="edit-tgl" name="tanggal_keluar"
                                            class="w-full h-11 px-4 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                                    {{-- Jumlah --}}
                                    <div class="flex flex-col gap-2">
                                        <label class="text-xs font-medium text-secondary uppercase tracking-wide">
                                            Jumlah Produk Keluar
                                        </label>
                                        <input type="number" id="edit-jumlah" name="jumlah" placeholder="0"
                                            class="w-full h-11 px-4 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition">
                                    </div>

                                    {{-- Status --}}
                                    <div class="flex flex-col gap-2 relative">
                                        <label class="text-xs font-medium text-secondary uppercase tracking-wide">
                                            Status
                                        </label>
                                        <select name="status" id="edit-status"
                                            class="appearance-none w-full h-11 px-4 pr-10 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none cursor-pointer">

                                            <option value="">-- Status --</option>
                                            <option value="draft">Draft</option>
                                            <option value="posted">Posted</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <i data-lucide="chevron-down"
                                            class="absolute right-3 top-[36px] size-4 text-secondary pointer-events-none"></i>
                                    </div>
                                </div>

                                {{-- INFORMASI PRODUK --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    {{-- Kategori --}}
                                    <div class="flex flex-col gap-2">
                                        <span class="text-xs font-medium text-secondary uppercase tracking-wide">
                                            Kategori Produk
                                        </span>
                                        <div
                                            class="flex items-center gap-3 h-11 px-4 border border-border rounded-xl bg-muted/30">
                                            <i data-lucide="tag" class="size-4 text-primary"></i>
                                            <span id="data-kategori" class="text-sm font-medium text-foreground">
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Harga --}}
                                    <div class="flex flex-col gap-2">
                                        <span class="text-xs font-medium text-secondary uppercase tracking-wide">
                                            Harga Satuan
                                        </span>
                                        <div
                                            class="flex items-center gap-2 h-11 px-4 border border-border rounded-xl bg-muted/30">
                                            <span id="data-harga" class="text-sm font-bold text-primary">
                                            </span>
                                            <span class="text-xs text-secondary">/</span>
                                            <span id="data-satuan" class="text-xs text-secondary">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- DESKRIPSI --}}
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-medium text-secondary uppercase tracking-wide">
                                        Deskripsi
                                    </label>
                                    <textarea rows="4" id="edit-keterangan" name="keterangan" placeholder="Tuliskan keterangan jika ada..."
                                        class="w-full px-4 py-3 border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none resize-none transition"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 mt-4 pt-6 border-t border-border">
                        <button type="button" onclick="switchView('list')"
                            class="px-6 py-2.5 rounded-xl font-bold text-secondary hover:bg-muted transition-colors cursor-pointer text-sm">Batal</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold bg-primary text-white hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 cursor-pointer flex items-center gap-2 text-sm">
                            <i data-lucide="save" class="size-4"></i>
                            <span>Update Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="view-stok-detail" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')"
                    class="hover:text-primary transition-colors">Produk</a>
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
                            <button onclick="editCurrentProduk()"
                                class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-all shadow-lg shadow-primary/20 flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                                <i data-lucide="edit-3" class="size-4"></i>
                                Edit Data
                            </button>
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
                            {{-- Dibuat Oleh --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Dibuat Oleh
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <div id="detail-creator-avatar"
                                        class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">
                                    </div>
                                    <span id="detail-creator" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>

                            {{-- Diposting Oleh --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Diposting Oleh
                                </p>
                                <div class="flex items-center gap-3 p-3.5 border border-border rounded-xl">
                                    <div id="detail-poster-avatar"
                                        class="size-8 rounded-full bg-success/10 flex items-center justify-center text-success text-xs font-bold">
                                    </div>
                                    <span id="detail-poster" class="font-semibold text-sm text-foreground"></span>
                                </div>
                            </div>
                            {{-- Jumlah --}}
                            <div>
                                <p class="text-xs text-secondary font-medium uppercase tracking-wider mb-2">
                                    Jumlah Produk Keluar
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

                            <div id="detail-desk"
                                class="p-5 border border-border font-semibold rounded-xl text-sm text-foreground leading-relaxed">
                            </div>
                        </div>
                    </div>

                    {{-- PERFORMA PRODUK --}}
                    {{-- <div class="border border-border rounded-2xl shadow-sm p-6 md:p-8">
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
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div id="status-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/30 backdrop-blur-sm transition-opacity">
        <div class="w-full max-w-md rounded-3xl border border-border bg-white/95 backdrop-blur dark:bg-neutral-900 shadow-xl transform transition-all scale-95 opacity-0"
            id="status-modal-card">
            <!-- HEADER -->
            <div class="p-6 border-b border-border flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-foreground">
                        Ubah Status Transaksi
                    </h3>
                    <p class="text-xs text-secondary mt-1">
                        Pilih status baru untuk transaksi stok
                    </p>
                </div>
                <button onclick="closeStatusModal()" class="p-2 rounded-lg hover:bg-muted transition-colors">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <!-- BODY -->
            <div class="p-6 flex flex-col gap-5">
                <input type="hidden" id="status-id">
                <div>
                    <label class="block text-xs font-semibold text-secondary uppercase tracking-wide mb-3">
                        Produk
                    </label>

                    <div
                        class="flex items-center gap-4 p-4 rounded-xl border border-border bg-muted/30 dark:bg-neutral-800/40">
                        <!-- FOTO -->
                        <div class="size-14 rounded-xl overflow-hidden flex items-center justify-center bg-primary/10">
                            <img id="status-produk-foto" class="w-full h-full object-cover hidden">
                            <i id="status-produk-icon" data-lucide="image" class="size-6 text-primary"></i>
                        </div>
                        <!-- INFO PRODUK -->
                        <div class="flex flex-col">
                            <p id="status-produk-nama" class="font-semibold text-foreground">
                                -
                            </p>
                            <p id="status-produk-kode" class="text-xs text-secondary">
                                -
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-secondary uppercase tracking-wide mb-2">
                        Status Transaksi
                    </label>
                    <div class="relative">
                        <select id="status-select"
                            class="w-full px-4 py-3 rounded-xl border border-border bg-muted/40 dark:bg-neutral-800 text-sm focus:ring-2 focus:ring-primary/20 outline-none appearance-none cursor-pointer">
                            <option value="draft">Draft</option>
                            <option value="posted">Posting</option>
                            <option value="cancelled">Cancel</option>
                        </select>
                        <i data-lucide="chevron-down"
                            class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                    </div>
                    <p id="status-warning" class="text-xs text-secondary"></p>
                </div>
                <!-- INFO BOX -->
                <div
                    class="flex items-center gap-3 p-4 rounded-xl border border-border bg-muted/30 text-sm text-secondary">
                    <i data-lucide="info" class="size-5 text-primary"></i>
                    <p>
                        Perubahan status akan langsung memperbarui transaksi stok.
                    </p>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="p-6 border-t border-border flex gap-3">
                <button onclick="closeStatusModal()"
                    class="flex-1 py-3 rounded-xl border border-border font-semibold text-sm hover:bg-muted transition-colors">
                    Batal
                </button>
                <button onclick="updateStatus()"
                    class="flex-1 py-3 rounded-xl bg-primary text-white font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                    Simpan Perubahan
                </button>
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
            const PRODUK_DATA = @json($produks);

            document.addEventListener("DOMContentLoaded", function() {
                const btnBuat = document.getElementById("btnBuatTransaksi");
                if (btnBuat) {
                    btnBuat.addEventListener("click", async function() {
                        try {
                            const csrf = document.querySelector('meta[name="csrf-token"]').content;
                            const res = await fetch("/admin/master/menu/stok-keluar", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": csrf,
                                    "Accept": "application/json",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({})
                            });
                            const data = await res.json();
                            if (!data.success) {
                                alert(data.message || "Gagal membuat transaksi");
                                return;
                            }

                            const trx = data.data;

                            document.getElementById("edit-id").value = trx.id;
                            document.getElementById("edit-kode").value = trx.kode_transaksi;
                            document.getElementById("edit-tgl").value =
                                new Date().toISOString().split("T")[0];
                            document.getElementById("edit-jumlah").value = 0;
                            document.getElementById("edit-status").value = "draft";

                            switchView("edit");
                        } catch (err) {
                            console.error(err);
                            alert("Gagal membuat transaksi");
                        }
                    });
                }

                const input = document.getElementById("produk_search_input");
                const dropdown = document.getElementById("produk_dropdown");
                const btnTambah = document.getElementById("btnTambahProduk");
                const clearBtn = document.getElementById("clear_produk");

                let selectedProduk = null;

                input.addEventListener("input", function() {

                    const keyword = this.value.toLowerCase();
                    dropdown.innerHTML = "";

                    if (keyword.length < 1) {
                        dropdown.classList.add("hidden");
                        return;
                    }

                    const results = PRODUK_DATA.filter(p =>
                        p.nama_produk.toLowerCase().includes(keyword) ||
                        p.kode_produk.toLowerCase().includes(keyword)
                    );

                    if (results.length === 0) {
                        dropdown.innerHTML = `
                            <div class="p-3 text-sm text-secondary">
                                Produk tidak ditemukan
                            </div>`;
                        dropdown.classList.remove("hidden");
                        return;
                    }

                    results.forEach(produk => {

                        const item = document.createElement("div");
                        item.className = "px-4 py-3 hover:bg-muted cursor-pointer text-sm";

                        item.innerHTML = `
                            <div class="font-medium">${produk.nama_produk}</div>
                            <div class="text-xs text-secondary">${produk.kode_produk}</div>
                        `;

                        item.addEventListener("click", function() {

                            selectedProduk = produk;

                            input.value = `${produk.nama_produk} - ${produk.kode_produk}`;

                            dropdown.classList.add("hidden");

                            btnTambah.disabled = false;

                            clearBtn.classList.remove("hidden");
                        });

                        dropdown.appendChild(item);
                    });

                    dropdown.classList.remove("hidden");

                });

                // CLEAR BUTTON (dipindah keluar loop)
                clearBtn.addEventListener("click", function() {

                    selectedProduk = null;

                    input.value = "";

                    dropdown.classList.add("hidden");

                    btnTambah.disabled = true;

                    clearBtn.classList.add("hidden");

                });

                btnTambah.addEventListener("click", async function() {

                    if (!selectedProduk) {
                        alert("Pilih produk dulu");
                        return;
                    }

                    try {

                        const csrf = document.querySelector('meta[name="csrf-token"]').content;

                        const res = await fetch("/admin/master/menu/stok-transaksi/keluar", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrf,
                                "Accept": "application/json",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                produk_id: selectedProduk.id
                            })
                        });

                        const data = await res.json();

                        if (!data.success) {
                            alert(data.message || "Gagal membuat transaksi");
                            return;
                        }

                        const trx = data.data;

                        // isi hidden field
                        document.getElementById("edit-id").value = trx.id;
                        document.getElementById("produk_id").value = selectedProduk.id;

                        // kode transaksi
                        document.getElementById("edit-kode").value = trx.kode_transaksi;

                        document.getElementById("edit-tgl").value =
                            new Date().toISOString().split("T")[0];
                        document.getElementById("edit-jumlah").value = 0;
                        document.getElementById("edit-status").value = "draft";

                        // isi informasi produk
                        document.getElementById("data-kode").innerText =
                            "Kode: " + selectedProduk.kode_produk;
                        document.getElementById("data-nama").innerText =
                            selectedProduk.nama_produk;
                        document.getElementById("data-kategori").innerText =
                            selectedProduk.kategori;
                        document.getElementById("data-satuan").innerText =
                            selectedProduk.satuan;
                        document.getElementById("data-harga").innerText =
                            formatRupiah(selectedProduk.harga);

                        const img = document.getElementById("data-image");

                        if (selectedProduk.foto_produk) {
                            img.src = "/produk/" + selectedProduk.foto_produk;
                        } else {
                            img.src = "https://via.placeholder.com/400x400?text=No+Image";
                        }

                        switchView("edit");
                    } catch (err) {
                        console.error(err);
                        alert("Gagal membuat transaksi");
                    }
                });
            });

            document.addEventListener("click", function(e) {

                const dropdown = document.getElementById("produk_dropdown");

                if (
                    !e.target.closest("#produk_search_input") &&
                    !e.target.closest("#produk_dropdown")
                ) {
                    dropdown.classList.add("hidden");
                }

            });

            // --------------------------------------------------
            // 1. Utility / Fungsi Umum
            // --------------------------------------------------
            function switchView(view) {
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                const viewMap = {
                    list: 'view-stok-list',
                    edit: 'view-stok-edit',
                    detail: 'view-stok-detail'
                };

                const targetId = viewMap[view] || 'view-stok-list';
                const target = document.getElementById(targetId);
                if (target) target.classList.remove('hidden');

                if (view !== 'edit') {
                    resetPreview('edit');
                }

                lucide.createIcons();
            }

            function resetPreview(mode) {
                let preview, placeholder, input, fileName;

                if (mode === 'add') {
                    preview = document.getElementById('logo-preview-add');
                    placeholder = document.getElementById('upload-placeholder-add');
                    input = document.getElementById('logo-input');
                    fileName = document.getElementById('file-name');
                } else if (mode === 'edit') {
                    preview = document.getElementById('logo-preview-edit');
                    placeholder = document.getElementById('upload-placeholder-edit');
                    input = document.getElementById('logo-edit');
                    fileName = document.getElementById('file-name-edit');
                }

                if (preview) preview.classList.add('hidden');
                if (placeholder) placeholder.classList.remove('hidden');
                if (input) input.value = '';
                if (fileName) {
                    fileName.classList.add('hidden');
                    fileName.textContent = '';
                }
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

                initEditView();
                initUploadPreview(); // untuk add & edit
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

                        if (data.empty) {
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-secondary">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="search-x" class="w-8 h-8"></i>
                                            <span>Data tidak ditemukan</span>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        } else {
                            tableBody.innerHTML = data.html;
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

            // --------------------------------------------------
            // 4. Edit View Logic
            // --------------------------------------------------
            function initEditView() {
                const jumlahEl = document.getElementById('edit-jumlah'); // sama id dengan add → hati-hati
                const hargaEl = document.getElementById('edit-harga');
                const totalEl = document.getElementById('edit-total');

                if (!jumlahEl || !hargaEl || !totalEl) return;

                function hitungTotalEdit() {
                    const jumlah = parseInt(jumlahEl.value) || 0;
                    const harga = parseInt(hargaEl.value) || 0;
                    totalEl.value = jumlah * harga;
                }

                jumlahEl.addEventListener('input', hitungTotalEdit);
                hargaEl.addEventListener('input', hitungTotalEdit);
            }

            // Fungsi dipanggil dari tombol edit di table
            function editData(id) {

                fetch(`/admin/master/menu/stok-keluar/${id}`)
                    .then(res => res.json())
                    .then(data => {

                        if (data.status === 'posted') {
                            alert("Transaksi sudah POSTED dan tidak bisa diedit");
                            return;
                        }

                        const statusEl = document.getElementById("data-status-text");

                        document.getElementById('edit-id').value = data.id;
                        document.getElementById('produk_id').value = data.produk_id;

                        document.getElementById('edit-kode').value = data.kode_transaksi;
                        document.getElementById('edit-tgl').value = data.tanggal_keluar;
                        document.getElementById('edit-jumlah').value = data.jumlah;
                        document.getElementById('edit-status').value = data.status;

                        document.getElementById("data-nama").innerText =
                            data.produk?.nama_produk;

                        document.getElementById("data-kode").innerText =
                            "Kode: " + data.produk?.kode_produk;

                        document.getElementById("data-kategori").innerText =
                            data.produk?.kategori;

                        document.getElementById("data-satuan").innerText =
                            data.produk?.satuan;

                        document.getElementById("data-harga").innerText =
                            formatRupiah(data.produk?.harga);

                        document.getElementById('edit-keterangan').value = data.keterangan;

                        const img = document.getElementById("data-image");

                        if (data.produk?.foto_produk) {
                            img.src = "/produk/" + data.produk.foto_produk;
                        } else {
                            img.src = "/asset/image/no-image.jpg";
                        }

                        statusEl.innerText =
                            data.produk?.status.charAt(0).toUpperCase() + data.produk?.status.slice(1);
                        if (data.produk?.status === 'aktif') {
                            statusEl.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-success/10 text-success";
                        } else if (data.produk?.status === 'nonaktif') {
                            statusEl.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-error/10 text-error";
                        }

                        switchView('edit');

                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memuat data');
                    });
            }

            // Handle submit form edit (AJAX)
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(editForm);
                    formData.append('_method', 'PUT');
                    const id = document.getElementById('edit-id').value;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    if (!id) {
                        alert('ID produk tidak ditemukan');
                        return;
                    }

                    if (!csrfToken) {
                        alert('CSRF token tidak ditemukan. Pastikan meta tag CSRF ada di halaman.');
                        console.error('CSRF meta tag missing');
                        return;
                    }

                    try {
                        const res = await fetch(`/admin/master/menu/stok-edit/keluar/${id}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        // Log response untuk debug
                        if (!res.ok) {
                            const text = await res.text();
                            console.log('Response error body:', text);
                            throw new Error(`Server error: ${res.status} ${res.statusText}`);
                        }
                        const result = await res.json();

                        if (result.success) {
                            alert(result.message || 'Produk berhasil diperbarui');
                            loadData(); // refresh table
                            switchView('list');
                        } else {
                            alert(result.message || 'Gagal update produk');
                        }
                    } catch (err) {
                        console.error('Update error:', err);
                        alert('Terjadi kesalahan saat menyimpan. Cek console untuk detail.');
                    }
                });
            }

            function editCurrentProduk() {
                if (!currentProdukId) {
                    alert('Produk tidak ditemukan');
                    return;
                }

                editData(currentProdukId);
            }

            // --------------------------------------------------
            // 5. Upload Preview (Add + Edit)
            // --------------------------------------------------
            function initUploadPreview() {
                // ----- View EDIT -----
                const editInput = document.getElementById('logo-edit');
                const editPreview = document.getElementById('logo-preview-edit');
                const editPlaceholder = document.getElementById('upload-placeholder-edit');
                const editError = document.getElementById('upload-error-edit');
                const editFileName = document.getElementById('file-name-edit');

                if (editInput) {
                    editInput.addEventListener('change', function() {
                        handleFileUpload(this, editPreview, editPlaceholder, editError, editFileName);
                    });
                }
            }

            function handleFileUpload(input, preview, placeholder, errorEl, fileNameEl) {
                const file = input.files[0];
                if (!file) return;

                const maxSize = 2 * 1024 * 1024; // 2MB

                // Reset error
                if (errorEl) {
                    errorEl.classList.add('hidden');
                    errorEl.textContent = '';
                }

                // Validasi
                if (file.size > maxSize) {
                    if (errorEl) {
                        errorEl.textContent = 'Ukuran file maksimal 2MB';
                        errorEl.classList.remove('hidden');
                    }
                    input.value = '';
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    if (errorEl) {
                        errorEl.textContent = 'File harus gambar (PNG/JPG)';
                        errorEl.classList.remove('hidden');
                    }
                    input.value = '';
                    return;
                }

                // Tampilkan nama file (opsional)
                if (fileNameEl) {
                    fileNameEl.textContent = file.name;
                    fileNameEl.classList.remove('hidden');
                }

                // Preview
                const reader = new FileReader();
                reader.onload = e => {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }

            // --------------------------------------------------
            // 6. Fungsi lain yang masih dipakai (opsional)
            // --------------------------------------------------
            function showDetail(id) {

                currentProdukId = id;

                fetch(`/admin/master/menu/stok-keluar/${id}`)
                    .then(res => res.json())
                    .then(data => {

                        const editBtn = document.querySelector('#view-stok-detail button');
                        const status1 = document.getElementById("detail-status-text");
                        // const stokEl = document.getElementById("detail-stok");
                        const status2 = document.getElementById("detail-status");

                        if (data.status === 'posted') {
                            editBtn.style.display = "none";
                        } else {
                            editBtn.style.display = "flex";
                        }

                        const img = document.getElementById("detail-image");

                        document.getElementById("detail-nama").innerText = data.produk?.nama_produk ?? "-";
                        document.getElementById("detail-kode").innerText = "Kode: " + (data.produk?.kode_produk ?? "-");

                        document.getElementById("detail-kode-transaksi").innerText = data.kode_transaksi;
                        document.getElementById("detail-tanggal").innerText = data.tanggal_keluar;
                        document.getElementById("detail-jumlah").innerText = data.jumlah;

                        document.getElementById("detail-harga").innerText =
                            formatRupiah(data.produk?.harga);

                        document.getElementById("detail-satuan").innerText =
                            data.produk?.satuan ?? "-";

                        document.getElementById("detail-kategori").innerText =
                            data.produk?.kategori ?? "-";

                        document.getElementById("detail-desk").innerText =
                            data.keterangan ?? "-";

                        if (data.produk?.foto_produk) {
                            img.src = "/produk/" + data.produk.foto_produk;
                        } else {
                            img.src = "https://via.placeholder.com/400x400?text=No+Image";
                        }

                        status1.innerText =
                            data.produk?.status.charAt(0).toUpperCase() + data.produk?.status.slice(1);
                        if (data.produk?.status === 'aktif') {
                            status1.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-success/10 text-success";
                        } else if (data.produk?.status === 'nonaktif') {
                            status1.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-error/10 text-error";
                        }

                        // CREATOR
                        const creatorName = data.creator?.username ?? "-";
                        document.getElementById("detail-creator").innerText = creatorName;

                        document.getElementById("detail-creator-avatar").innerText =
                            creatorName !== "-" ? creatorName.substring(0, 2).toUpperCase() : "--";

                        // POSTER
                        const posterName = data.poster?.username ?? "Admin";
                        document.getElementById("detail-poster").innerText = posterName;

                        document.getElementById("detail-poster-avatar").innerText =
                            posterName !== "Belum diposting" ?
                            posterName.substring(0, 2).toUpperCase() :
                            "--";

                        // Status
                        status2.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        if (data.status === 'draft') {
                            status2.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-warning/10 text-warning";
                        } else if (data.status === 'posted') {
                            status2.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-success/10 text-success";
                        } else if (data.status === 'cancelled') {
                            status2.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-error/10 text-error";
                        }

                        switchView('detail');

                    });
            }

            function deleteData(id) {

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                if (!confirm('Yakin ingin menghapus data ini?')) return;

                fetch(`/admin/master/menu/stok-hapus/keluar/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            loadData();
                            alert('Data berhasil dihapus');
                        } else {
                            alert(result.message || 'Gagal menghapus data');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat menghapus');
                    });
            }

            // --------------------------------------------------
            // &. Update Status
            // --------------------------------------------------
            function openStatusModal(id, status, produk) {

                document.getElementById("status-id").value = id;

                const select = document.getElementById("status-select");

                // reset option
                select.innerHTML = `
                    <option value="draft">Draft</option>
                    <option value="posted">Posting</option>
                    <option value="cancelled">Cancel</option>
                `;

                select.value = status;
                select.disabled = false;

                // RULE STATUS
                if (status === "posted") {

                    select.innerHTML = `
                        <option value="posted">Posting</option>
                        <option value="cancelled">Cancel</option>
                    `;

                    select.value = "posted";

                }

                if (status === "cancelled") {

                    select.innerHTML = `
                        <option value="cancelled">Cancel</option>
                    `;

                    select.disabled = true;

                }

                // PRODUK
                document.getElementById("status-produk-nama").innerText =
                    produk.nama_produk;

                document.getElementById("status-produk-kode").innerText =
                    "Kode: " + produk.kode_produk;

                const img = document.getElementById("status-produk-foto");
                const icon = document.getElementById("status-produk-icon");

                if (produk.foto_produk) {

                    img.src = "/produk/" + produk.foto_produk;
                    img.classList.remove("hidden");
                    icon.classList.add("hidden");

                } else {

                    img.classList.add("hidden");
                    icon.classList.remove("hidden");

                }

                // OPEN MODAL
                const modal = document.getElementById("status-modal");
                const card = document.getElementById("status-modal-card");

                modal.classList.remove("hidden");
                modal.classList.add("flex");

                setTimeout(() => {
                    card.classList.remove("scale-95", "opacity-0");
                    card.classList.add("scale-100", "opacity-100");
                }, 10);

                const warning = document.getElementById("status-warning");

                if (status === "posted") {
                    warning.innerText = "Transaksi yang sudah diposting hanya dapat dibatalkan.";
                } else if (status === "cancelled") {
                    warning.innerText = "Transaksi yang sudah dibatalkan tidak dapat diubah lagi.";
                } else {
                    warning.innerText = "";
                }

            }

            function closeStatusModal() {

                const modal = document.getElementById("status-modal");
                const card = document.getElementById("status-modal-card");

                card.classList.add("scale-95", "opacity-0");
                card.classList.remove("scale-100", "opacity-100");

                setTimeout(() => {

                    modal.classList.add("hidden");
                    modal.classList.remove("flex");

                }, 200);

            }

            async function updateStatus() {

                const id = document.getElementById("status-id").value;
                const status = document.getElementById("status-select").value;
                const csrf = document.querySelector('meta[name="csrf-token"]').content;

                const btn = event.target;

                btn.innerHTML = "Menyimpan...";
                btn.disabled = true;

                try {

                    const res = await fetch(`/admin/master/menu/stok-status/keluar/${id}`, {

                        method: "PUT",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf,
                            "Accept": "application/json"
                        },

                        body: JSON.stringify({
                            status
                        })

                    });

                    const result = await res.json();

                    if (result.success) {

                        closeStatusModal();

                        loadData();

                    } else {

                        alert(result.message || "Gagal update status");

                    }

                } catch (err) {

                    console.error(err);
                    alert("Terjadi kesalahan");

                }

                btn.innerHTML = "Simpan Perubahan";
                btn.disabled = false;

            }

            // AUTO RELOAD
            let lastStokHTML = '';
            let lastActivityHTML = '';
            let refreshInterval = null;

            function loadStokKeluar(page = 1) {
                const search = document.getElementById('produkSearch')?.value || '';
                fetch(`?page=${page}&search=${encodeURIComponent(search)}&type=stok`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const tableBody = document.getElementById('stokTable');
                        if (!tableBody) return;

                        if (data.empty) {
                            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-10 text-secondary">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="search-x" class="w-8 h-8"></i>
                            <span>Data tidak ditemukan</span>
                        </div>
                    </td>
                </tr>`;
                        } else if (data.html && lastStokHTML !== data.html) {
                            tableBody.innerHTML = data.html;
                            lastStokHTML = data.html;
                            animateRows('#stokTable tr');
                        }
                        lucide.createIcons();
                    })
                    .catch(err => console.error('Stok load error:', err));
            }

            function loadActivity() {
                const activityTable = document.getElementById('activityTable');
                if (!activityTable) return;

                const url = activityTable.dataset.url || window.location.href;

                fetch(`${url}?type=activity`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.html) return;

                        if (lastActivityHTML !== data.html) {
                            activityTable.innerHTML = data.html;
                            lastActivityHTML = data.html;
                            animateRows('#activityTable tr');
                        }
                        lucide.createIcons();
                    })
                    .catch(err => console.error('Activity load error:', err));
            }

            function animateRows(selector) {
                const rows = document.querySelectorAll(selector);
                rows.forEach((row, index) => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(8px)';
                    setTimeout(() => {
                        row.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    }, index * 35);
                });
            }

            // Satu interval untuk kedua tabel
            function startAutoRefresh() {
                if (refreshInterval) clearInterval(refreshInterval);

                refreshInterval = setInterval(() => {
                    loadStokKeluar();
                    loadActivity();
                }, 5000); // 5 detik
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Inisialisasi pertama
                loadStokKeluar();
                loadActivity();

                // Mulai auto refresh
                startAutoRefresh();

                // Handle pagination (sudah ada di kode kamu)
                document.addEventListener('click', e => {
                    const link = e.target.closest('.pagination a');
                    if (link) {
                        e.preventDefault();
                        const url = new URL(link.href);
                        const page = url.searchParams.get('page');
                        if (page) loadStokKeluar(page);
                    }
                });

                // Stop interval saat tab tidak aktif (hemat resource)
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        if (refreshInterval) clearInterval(refreshInterval);
                    } else {
                        startAutoRefresh();
                        // Refresh sekali saat kembali ke tab
                        loadStokKeluar();
                        loadActivity();
                    }
                });
            });
        </script>
    @endpush

</x-view.layout.app>
