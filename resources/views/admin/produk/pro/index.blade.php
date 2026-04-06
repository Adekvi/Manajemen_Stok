<x-view.layout.app title="Data Produk">

    <div id="view-stok-list" class="view-section hidden flex flex-col flex-1 h-full">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
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

                    <button onclick="switchView('add')"
                        class="bg-primary text-white rounded-xl px-5 py-2.5 font-bold hover:bg-primary-hover transition-colors flex items-center justify-center gap-2 text-sm cursor-pointer shadow-sm shadow-primary/20">
                        <i data-lucide="plus" class="size-4"></i>
                        <span>Tambah Produk</span>
                    </button>
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
                            @include('admin.produk.pro.table')
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

    <div id="view-stok-add" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')" class="hover:text-primary transition-colors">Produk</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <span class="font-medium text-foreground">Tambah Produk</span>
            </div>
            <div class=" border border-border rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-border">
                    <h3 class="font-bold text-lg text-foreground">Informasi Produk</h3>
                    <p class="text-sm text-secondary mt-1">Lengkapi form di bawah untuk menambahkan produk</p>
                </div>
                <form class="p-6 md:p-8 flex flex-col gap-6" action="{{ route('admin.produk.tambah') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                        <!-- Upload Logo -->
                        <div class="w-full flex flex-col items-center justify-center text-center gap-2">
                            <div id="upload-area-add"
                                class="w-28 h-28 sm:w-32 sm:h-32 bg-muted rounded-2xl flex items-center justify-center border-2 border-dashed border-secondary/40 cursor-pointer hover:border-primary hover:bg-primary/5 transition-all duration-200 group overflow-hidden relative mx-auto">

                                <!-- Preview gambar -->
                                <img id="logo-preview-add" class="w-full h-full object-cover hidden"
                                    alt="Preview logo produk">

                                <!-- Placeholder -->
                                <div id="upload-placeholder-add" class="text-center z-10">
                                    <i data-lucide="upload-cloud"
                                        class="size-7 text-secondary group-hover:text-primary mx-auto mb-1.5"></i>
                                    <span class="text-xs font-medium text-secondary group-hover:text-primary">
                                        Upload Produk
                                    </span>
                                </div>

                                <!-- Input File -->
                                <input type="file" id="logo-input" name="foto_produk" accept="image/png,image/jpeg"
                                    class="absolute inset-0 opacity-0 cursor-pointer z-20">
                            </div>
                            <p id="file-name"
                                class="text-xs text-primary font-medium hidden max-w-[160px] truncate text-center">
                            </p>
                            <div class="text-xs text-secondary/80 text-center max-w-[160px]">
                                <p>PNG / JPG • Maksimal 2 MB</p>
                                <p id="upload-error" class="text-error mt-1 hidden"></p>
                            </div>
                        </div>

                        <!-- Form Input -->
                        <div class="flex flex-col gap-4">
                            <!-- Kode Produk -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-foreground">
                                    Kode Produk <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="kode_produk_display" name="kode_produk_display"
                                        placeholder="ORD-00001" readonly
                                        class="w-full px-4 py-3 rounded-xl border border-border cursor-not-allowed focus:ring-1 focus:ring-primary outline-none transition-all text-sm">
                                    <div id="kode-loading" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                        <svg class="animate-spin h-5 w-5 text-primary"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <input type="hidden" name="kode_produk" id="kode_produk_hidden">
                            </div>
                            <!-- Nama Produk -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-foreground">
                                    Nama Produk <span class="text-error">*</span>
                                </label>
                                <input type="text" name="nama_produk" placeholder="Contoh: Brownies 100gr"
                                    class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none transition-all text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Satuan </label>
                            <input type="text" name="satuan" placeholder="Contoh: Pcs"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Stok </label>
                            <input type="number" name="stok" placeholder="0"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Kategori </label>
                            <input type="text" name="kategori" placeholder="Kategori"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">
                                Harga Satuan <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-medium text-sm">Rp</span>
                                <input type="number" id="harga" name="harga" placeholder="0"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none transition-all text-sm">
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Status </label>
                            <select name="status"
                                class="appearance-none border border-border rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                                <option value="">-- Status --</option>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                            <i data-lucide="chevron-down"
                                class="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-foreground">Deskripsi</label>
                        <textarea rows="4" name="keterangan" placeholder="Tuliskan spesifikasi atau deskripsi detail produk..."
                            class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm resize-none"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-4 mt-4 pt-6 border-t border-border">
                        <button type="button" onclick="switchView('list')"
                            class="px-6 py-2.5 rounded-xl font-bold text-secondary hover:bg-muted transition-colors cursor-pointer text-sm">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold bg-primary text-white hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 cursor-pointer flex items-center gap-2 text-sm">
                            <i data-lucide="save" class="size-4"></i>
                            <span>Simpan Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="view-stok-edit" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
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
                    <p class="text-sm text-secondary mt-1">Ubah data produk.</p>
                </div>
                <form id="editForm" class="p-6 md:p-8 flex flex-col gap-6" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-id" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                        <!-- Upload Logo -->
                        <div class="w-full flex flex-col items-center justify-center text-center gap-2">
                            <div id="upload-area-edit"
                                class="w-28 h-28 sm:w-32 sm:h-32 bg-muted rounded-2xl flex items-center justify-center border-2 border-dashed border-secondary/40 cursor-pointer hover:border-primary hover:bg-primary/5 transition-all duration-200 group overflow-hidden relative mx-auto">

                                <!-- Preview gambar (awalnya disembunyikan) -->
                                <img id="logo-preview-edit" class="w-full h-full object-cover hidden"
                                    alt="Preview logo produk">

                                <!-- Placeholder default (ikon + teks) -->
                                <div id="upload-placeholder-edit" class="text-center z-10">
                                    <i data-lucide="upload-cloud"
                                        class="size-7 text-secondary group-hover:text-primary mx-auto mb-1.5"></i>
                                    <span class="text-xs font-medium text-secondary group-hover:text-primary">
                                        Upload Produk
                                    </span>
                                </div>

                                <!-- Input file (tersembunyi tapi clickable) -->
                                <input type="file" id="logo-edit" name="foto_produk"
                                    accept="image/png,image/jpeg"
                                    class="absolute inset-0 opacity-0 cursor-pointer z-20">
                            </div>

                            <p id="file-name-edit"
                                class="text-xs text-primary font-medium hidden max-w-[140px] truncate text-center">
                            </p>

                            <div class="text-xs text-secondary/80 text-center max-w-[140px]">
                                <p>PNG / JPG • Maksimal 2 MB</p>
                                <p id="upload-error-edit" class="text-error mt-1 hidden"></p>
                            </div>
                        </div>
                        <!-- Form Input -->
                        <div class="flex flex-col gap-4">
                            <!-- Kode Produk -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-foreground">
                                    Kode Produk <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="kode_produk_edit" name="kode_produk_display"
                                        placeholder="ORD-00001" readonly
                                        class="w-full px-4 py-3 rounded-xl border border-border cursor-not-allowed focus:ring-1 focus:ring-primary outline-none transition-all text-sm">
                                    <div id="kode-loading" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                        <svg class="animate-spin h-5 w-5 text-primary"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <input type="hidden" name="kode_produk" id="kode_produk_hidden_edit">
                            </div>
                            <!-- Nama Produk -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-foreground">
                                    Nama Produk <span class="text-error">*</span>
                                </label>
                                <input id="edit-nama" type="text" name="nama_produk"
                                    class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none transition-all text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label id="edit-satuan-label" class="text-sm font-semibold text-foreground">Satuan
                            </label>
                            <input type="text" id="edit-satuan" name="satuan"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Kategori </label>
                            <input id="edit-kategori" type="text" name="kategori"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">
                                Harga Satuan <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-medium text-sm">Rp</span>
                                <input type="number" id="edit-harga" name="harga" placeholder="0"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none transition-all text-sm">
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Status </label>
                            <select name="status" id="edit-status"
                                class="appearance-none border border-border rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                                <option value="">-- Status --</option>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                            <i data-lucide="chevron-down"
                                class="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-foreground">Deskripsi</label>
                        <textarea rows="4" id="edit-deskripsi" name="keterangan"
                            class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  transition-all text-sm resize-none"></textarea>
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
                <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
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
                    <div class=" border border-border rounded-2xl shadow-sm p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-border">
                            <div>
                                <h3 class="font-bold text-lg text-foreground">Informasi Detail</h3>
                                <p class="text-sm text-secondary mt-1">Spesifikasi dan harga produk saat ini.</p>
                            </div>
                            <button onclick="editCurrentProduk()"
                                class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-all shadow-lg shadow-primary/20 flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5"><i
                                    data-lucide="edit-3" class="size-4"></i> Edit Data
                            </button>
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
                                    class="p-5 border border-border rounded-xl font-semibold text-sm text-foreground leading-relaxed">
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
                    add: 'view-stok-add',
                    edit: 'view-stok-edit',
                    detail: 'view-stok-detail'
                };

                const targetId = viewMap[view] || 'view-stok-list';
                const target = document.getElementById(targetId);
                if (target) target.classList.remove('hidden');

                // Reset preview saat ganti view (agar tidak ada gambar "hantu" dari view sebelumnya)
                if (view !== 'add') {
                    resetPreview('add');
                }
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

            // Fungsi dipanggil dari tombol edit di table
            function editData(id) {
                fetch(`/admin/master/menu/produk-view/${id}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Gagal memuat data');
                        return res.json();
                    })
                    .then(data => {
                        // Isi field lainnya (tetap sama seperti sebelumnya)
                        document.getElementById('edit-id').value = data.id;
                        document.getElementById('kode_produk_edit').value = data.kode_produk;
                        document.getElementById('kode_produk_hidden_edit').value = data.kode_produk;
                        document.getElementById('edit-nama').value = data.nama_produk || '';
                        document.getElementById('edit-satuan').value = data.satuan || '';
                        // document.getElementById('edit-stok').value = data.stok || '';
                        document.getElementById('edit-status').value = data.status || '';
                        document.getElementById('edit-kategori').value = data.kategori || '';
                        document.getElementById('edit-harga').value = formatRupiah(data.harga || 0).replace(/[^0-9]/g, '');
                        document.getElementById('edit-deskripsi').value = data.keterangan || '';

                        // Preview FOTO khusus EDIT
                        const editPreview = document.getElementById('logo-preview-edit');
                        const editPlaceholder = document.getElementById('upload-placeholder-edit');

                        if (data.foto_produk && editPreview) {
                            editPreview.src = `/produk/${data.foto_produk}`;
                            editPreview.classList.remove('hidden');
                            if (editPlaceholder) editPlaceholder.classList.add('hidden');
                        } else if (editPreview && editPlaceholder) {
                            editPreview.classList.add('hidden');
                            editPlaceholder.classList.remove('hidden');
                        }

                        switchView('edit');

                        // Trigger hitung total
                        document.getElementById('edit-harga')?.dispatchEvent(new Event('input'));
                    })
                    .catch(err => {
                        console.error('Edit load error:', err);
                        alert('Gagal memuat data produk');
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
                        const res = await fetch(`/admin/master/menu/produk-edit/${id}`, {
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
            // 6. Upload Preview (Add + Edit)
            // --------------------------------------------------
            function initUploadPreview() {
                // ----- View ADD -----
                const addInput = document.getElementById('logo-input');
                const addPreview = document.getElementById('logo-preview-add');
                const addPlaceholder = document.getElementById('upload-placeholder-add');
                const addError = document.getElementById('upload-error');
                const addFileName = document.getElementById('file-name');

                if (addInput) {
                    addInput.addEventListener('change', function() {
                        handleFileUpload(this, addPreview, addPlaceholder, addError, addFileName);
                    });
                }

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
            // 7. Fungsi lain yang masih dipakai (opsional)
            // --------------------------------------------------
            function showDetail(id) {

                currentProdukId = id;

                fetch(`/admin/master/menu/produk-view/${id}`)
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

                        document.getElementById("detail-harga").innerText =
                            "Rp " + Number(data.harga || 0).toLocaleString('id-ID');

                        document.getElementById("detail-des").innerText =
                            data.keterangan ?? "-";

                        if (data.foto_produk) {
                            img.src = "/produk/" + data.foto_produk;
                        } else {
                            img.src = "https://via.placeholder.com/400x400?text=No+Image";
                        }

                        statusEl.innerText =
                            data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        if (data.status === 'aktif') {
                            statusEl.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-success/10 text-success";
                        } else if (data.status === 'nonaktif') {
                            statusEl.className = "px-3 py-1.5 rounded-full text-xs font-bold bg-error/10 text-error";
                        }

                        lucide.createIcons();

                        switchView('detail');
                    })
                    .catch(err => console.error(err));
            }

            function deleteData(id) {

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                if (!confirm('Yakin ingin menghapus data ini?')) return;

                fetch(`/admin/master/menu/produk-hapus/${id}`, {
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

            // Auto generate kode (hanya sekali saat load halaman)
            document.addEventListener('DOMContentLoaded', function() {
                const display = document.getElementById('kode_produk_display');
                const hidden = document.getElementById('kode_produk_hidden');
                const loading = document.getElementById('kode-loading');

                if (!display || !loading) return;

                loading.classList.remove('hidden');

                fetch('{{ route('admin.produk.generate-kode') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            display.value = data.kode;
                            hidden.value = data.kode;
                        } else {
                            display.value = 'Gagal generate kode';
                        }
                    })
                    .catch(() => {
                        display.value = 'Error memuat kode';
                    })
                    .finally(() => {
                        loading.classList.add('hidden');
                    });
            });
        </script>
    @endpush

</x-view.layout.app>
