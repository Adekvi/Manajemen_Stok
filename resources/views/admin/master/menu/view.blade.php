<x-view.layout.app title="Menu Akses">

    <div id="view-menu" class="view-section flex flex-col flex-1 h-full">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Menu Akses</span>
        </div>

        <div class="flex flex-col gap-6 mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="relative w-full md:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-lg">Kelola Menu</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <div class="relative flex-1 max-w-md">
                        <i data-lucide="search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                        <input type="text" id="menuSearch" placeholder="Cari menu..." value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:border-primary outline-none transition-all">
                    </div>

                    {{-- <button onclick="switchView('add')"
                        class="bg-primary text-white rounded-xl px-5 py-2.5 font-bold hover:bg-primary-hover transition-colors flex items-center justify-center gap-2 text-sm cursor-pointer shadow-sm shadow-primary/20">
                        <i data-lucide="plus" class="size-4"></i>
                        <span>Tambah Menu</span>
                    </button> --}}
                </div>
            </div>

            <div class=" border border-border rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-muted/50 border-b border-border">
                            <tr>
                                <th class="p-4 pl-6 font-semibold text-secondary">No</th>
                                <th class="p-4 pl-6 font-semibold text-secondary">Nama Menu</th>
                                <th class="p-4 font-semibold text-secondary">Alamat Url</th>
                                <th class="p-4 font-semibold text-secondary">Icon</th>
                                <th class="p-4 font-semibold text-secondary">Status</th>
                                {{-- <th class="p-4 text-center font-semibold text-secondary">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border" id="stokTable">
                            @include('admin.master.menu.table')
                        </tbody>
                    </table>
                </div>
                <div
                    class="p-4 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-secondary">
                    <p>
                        Menampilkan {{ $menus->firstItem() }} - {{ $menus->lastItem() }}
                        dari {{ $menus->total() }} menu
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="flex gap-2">
                            {{-- tombol previous --}}
                            @if ($menus->onFirstPage())
                                <span
                                    class="size-9 flex items-center justify-center rounded-lg border border-border opacity-50">
                                    <i data-lucide="chevron-left" class="size-4"></i>
                                </span>
                            @else
                                <a href="{{ $menus->previousPageUrl() }}"
                                    class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                    <i data-lucide="chevron-left" class="size-4"></i>
                                </a>
                            @endif
                            {{-- nomor halaman --}}
                            @for ($i = 1; $i <= $menus->lastPage(); $i++)
                                @if ($i == $menus->currentPage())
                                    <span
                                        class="size-9 flex items-center justify-center rounded-lg bg-primary text-white shadow-md shadow-primary/20 cursor-pointer">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $menus->url($i) }}"
                                        class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            {{-- tombol next --}}
                            @if ($menus->hasMorePages())
                                <a href="{{ $menus->nextPageUrl() }}"
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

    {{-- <div id="view-menu-add" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')" class="hover:text-primary transition-colors">Menu
                    Akses</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <span class="font-medium text-foreground">Tambah Menu</span>
            </div>
            <div class=" border border-border rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-border">
                    <h3 class="font-bold text-lg text-foreground">Informasi Menu</h3>
                    <p class="text-sm text-secondary mt-1">Lengkapi form di bawah untuk menambahkan menu</p>
                </div>
                <form class="p-6 md:p-8 flex flex-col gap-6" action="{{ route('admin.menu.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- NAMA MENU -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">
                                Nama Menu <span class="text-error">*</span>
                            </label>
                            <input type="text" name="name" placeholder="Contoh: Dashboard"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none text-sm">
                        </div>

                        <!-- ROUTE -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">
                                Route <span class="text-error">*</span>
                            </label>
                            <input type="text" name="route" placeholder="admin.dashboard"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none text-sm">
                        </div>

                        <!-- ICON -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Icon</label>
                            <input type="text" name="icon" placeholder="layout-dashboard"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none text-sm">
                        </div>

                        <!-- GROUP -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Group</label>
                            <input type="text" name="group" placeholder="Menu Utama / Management"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none text-sm">
                        </div>

                        <!-- ORDER -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Urutan</label>
                            <input type="number" name="order" placeholder="1"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none text-sm">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">
                                Role <span class="text-error">*</span>
                            </label>
                            <select name="role" class="border border-border rounded-xl px-4 py-2.5 text-sm">
                                <option value="admin">Admin</option>
                                <option value="user">Staff</option>
                            </select>
                        </div>

                        <!-- STATUS -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Status</label>
                            <select name="is_active" class="border border-border rounded-xl px-4 py-2.5 text-sm">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-4 mt-4 pt-6 border-t border-border">
                        <button type="button" onclick="switchView('list')"
                            class="px-6 py-2.5 rounded-xl font-bold text-secondary hover:bg-muted text-sm">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold bg-primary text-white hover:bg-primary-hover flex items-center gap-2 text-sm">
                            <i data-lucide="save" class="size-4"></i>
                            <span>Simpan Menu</span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div id="view-menu-edit" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex flex-col gap-6 mb-10">
            <div class="flex items-center gap-2 mb-3 text-sm text-secondary">
                <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                    class="hover:text-primary transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <a href="#" onclick="switchView('list')" class="hover:text-primary transition-colors">Menu
                    Akses</a>
                <i data-lucide="chevron-right" class="size-4"></i>
                <span class="font-medium text-foreground">Edit Menu</span>
            </div>
            <div class=" border border-border rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-border">
                    <h3 class="font-bold text-lg text-foreground">Informasi Menu</h3>
                    <p class="text-sm text-secondary mt-1">Ubah menu.</p>
                </div>
                <form id="editForm" class="p-6 md:p-8 flex flex-col gap-6" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-id" name="id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- NAMA -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Nama Menu</label>
                            <input id="edit-name" type="text" name="name"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm">
                        </div>

                        <!-- ROUTE -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Route</label>
                            <input id="edit-route" type="text" name="route"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm">
                        </div>

                        <!-- ICON -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Icon</label>
                            <input id="edit-icon" type="text" name="icon"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm">
                        </div>

                        <!-- GROUP -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Group</label>
                            <input id="edit-group" type="text" name="group"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm">
                        </div>

                        <!-- ORDER -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Urutan</label>
                            <input id="edit-order" type="number" name="order"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm">
                        </div>

                        <!-- STATUS -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-foreground">Status</label>
                            <select id="edit-status" name="is_active"
                                class="border border-border rounded-xl px-4 py-2.5 text-sm">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-4 mt-4 pt-6 border-t border-border">
                        <button type="button" onclick="switchView('list')"
                            class="px-6 py-2.5 rounded-xl font-bold text-secondary hover:bg-muted text-sm">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold bg-primary text-white hover:bg-primary-hover flex items-center gap-2 text-sm">
                            <i data-lucide="save" class="size-4"></i>
                            <span>Update Menu</span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div> --}}

    @push('css')
    @endpush

    @push('js')
        <script>
            function switchView(view) {
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                const viewMap = {
                    list: 'view-menu',
                    add: 'view-menu-add',
                    edit: 'view-menu-edit',
                    detail: 'view-menu-detail'
                };

                const targetId = viewMap[view] || 'view-menu';
                const target = document.getElementById(targetId);

                if (target) target.classList.remove('hidden');

                lucide.createIcons();
            }

            // Search
            document.addEventListener('DOMContentLoaded', () => {
                // Default tampilkan list
                switchView('list');

                // Refresh lucide icons
                lucide.createIcons();

                // Event listener search (debounce)
                const searchInput = document.getElementById('menuSearch');

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
                                        <span>Menu tidak ditemukan</span>
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
        </script>
    @endpush

</x-view.layout.app>
