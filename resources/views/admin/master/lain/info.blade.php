<x-view.layout.app title="Pengumuman">

    <div id="view-announcements" class="page-section">
        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Pengumuman</span>
        </div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="relative flex-1 max-w-md">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                <input type="text" id="announcementSearch" placeholder="Cari judul pengumuman..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-border  focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                    oninput="filterAnnouncements()">
            </div>
            <div class="flex gap-3">
                <select id="announcementStatusFilter" onchange="filterAnnouncements()"
                    class=" border border-border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary/20 text-sm font-medium">
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Non-Aktif</option>
                </select>
                <button onclick="openModalAnnouncement()"
                    class="bg-primary hover:bg-primary-hover text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 shadow-lg shadow-primary/20 transition-all cursor-pointer">
                    <i data-lucide="plus" class="size-5"></i>
                    <span class="hidden sm:inline">Tambah Pengumuman</span>
                </button>
            </div>
        </div>

        <div class=" rounded-2xl border border-border overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="p-4 font-semibold text-sm text-secondary">Judul Pengumuman</th>
                            <th class="p-4 font-semibold text-sm text-secondary">Tanggal Posting</th>
                            <th class="p-4 font-semibold text-sm text-secondary">Target</th>
                            <th class="p-4 font-semibold text-sm text-secondary">Status</th>
                            <th class="p-4 font-semibold text-sm text-secondary text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="announcementsTableBody">
                        @include('admin.master.lain.table')
                    </tbody>
                </table>
            </div>
            <div
                class="p-4 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-secondary">
                <p>
                    Menampilkan {{ $info->firstItem() }} - {{ $info->lastItem() }}
                </p>
                <div class="flex items-center gap-2">
                    <div class="flex gap-2">
                        {{-- tombol previous --}}
                        @if ($info->onFirstPage())
                            <span
                                class="size-9 flex items-center justify-center rounded-lg border border-border opacity-50">
                                <i data-lucide="chevron-left" class="size-4"></i>
                            </span>
                        @else
                            <a href="{{ $info->previousPageUrl() }}"
                                class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                <i data-lucide="chevron-left" class="size-4"></i>
                            </a>
                        @endif
                        {{-- nomor halaman --}}
                        @for ($i = 1; $i <= $info->lastPage(); $i++)
                            @if ($i == $info->currentPage())
                                <span
                                    class="size-9 flex items-center justify-center rounded-lg bg-primary text-white shadow-md shadow-primary/20 cursor-pointer">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $info->url($i) }}"
                                    class="size-9 flex items-center justify-center rounded-lg border border-border hover:bg-muted cursor-pointer transition-colors">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor
                        {{-- tombol next --}}
                        @if ($info->hasMorePages())
                            <a href="{{ $info->nextPageUrl() }}"
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

    {{-- MODAL ADD --}}
    <div id="modalAnnouncement"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0">
        <div id="modalContentAdd"
            class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-border transform transition-all duration-300 scale-95 opacity-0">
            <!-- isi modal -->
            <form action="{{ route('admin.info.store') }}" method="POST">
                @csrf

                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <i data-lucide="megaphone" class="size-5 text-primary"></i>
                        Tambah Pengumuman
                    </h2>

                    <button type="button" onclick="closeModalAnnouncement()"
                        class="p-2 rounded-lg hover:bg-muted transition">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="p-6 space-y-6">

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">
                            Judul Pengumuman
                        </label>

                        <input type="text" name="judul" required
                            class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus:bg-white focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                            placeholder="Contoh: Pemadaman Listrik Area A">
                    </div>

                    <!-- Tanggal & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">
                                Tanggal Pengumuman
                            </label>

                            <input type="date" name="tgl" required
                                class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus:bg-white focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">
                                Status
                            </label>

                            <select name="status"
                                class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus:bg-white focus:ring-2 focus:ring-primary/20 outline-none transition-all">

                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>

                            </select>
                        </div>

                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">
                            Isi Pengumuman
                        </label>

                        <textarea name="konten" rows="5" required
                            class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus:bg-white focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                            placeholder="Tuliskan isi pengumuman secara lengkap..."></textarea>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-border bg-muted/30 rounded-b-2xl">

                    <button type="button" onclick="closeModalAnnouncement()"
                        class="px-4 py-2 rounded-xl border border-border text-sm font-medium hover:bg-muted transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-hover transition flex items-center gap-2">

                        <i data-lucide="save" class="size-4"></i>
                        Simpan Pengumuman

                    </button>

                </div>

            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEditAnnouncement"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4 transition-opacity duration-300 opacity-0">
        <div id="modalContentEdit"
            class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-border transform transition-all duration-300 scale-95 opacity-0">
            <form id="formEditAnnouncement" method="POST">
                @csrf
                @method('PUT')
                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <i data-lucide="edit-3" class="size-5 text-primary"></i>
                        Edit Pengumuman
                    </h2>
                    <button type="button" onclick="closeEditModal()"
                        class="p-2 rounded-lg hover:bg-muted transition">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>
                <!-- BODY -->
                <div class="p-6 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">
                            Judul Pengumuman
                        </label>
                        <input type="text" name="judul" id="editJudul" required
                            class="w-full px-4 py-3 rounded-xl border border-border">
                    </div>
                    <!-- Tanggal & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">
                                Tanggal
                            </label>
                            <input type="date" name="tgl" id="editTgl" required
                                class="w-full px-4 py-3 rounded-xl border border-border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">
                                Status
                            </label>
                            <select name="status" id="editStatus"
                                class="w-full px-4 py-3 rounded-xl border border-border">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">
                            Isi Pengumuman
                        </label>
                        <textarea name="konten" id="editKonten" rows="5" required
                            class="w-full px-4 py-3 rounded-xl border border-border"></textarea>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-border bg-muted/30">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded-xl border border-border">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-white flex items-center gap-2">
                        <i data-lucide="save" class="size-4"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                switchView('dashboard');
                lucide.createIcons();
                initAnnouncementSearch();
            });

            /* ==============================
               INIT AJAX SEARCH
            ============================== */

            function initAnnouncementSearch() {
                const searchInput = document.getElementById('announcementSearch');
                const statusFilter = document.getElementById('announcementStatusFilter');

                let debounceTimer;

                searchInput.addEventListener('keyup', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        loadAnnouncements();
                    }, 400);
                });

                statusFilter.addEventListener('change', function() {
                    loadAnnouncements();
                });
            }


            /* ==============================
               LOAD DATA AJAX
            ============================== */

            function loadAnnouncements(page = 1) {

                const search = document.getElementById('announcementSearch').value;
                const status = document.getElementById('announcementStatusFilter').value;

                fetch(`?search=${encodeURIComponent(search)}&status=${status}&page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const tbody = document.getElementById('announcementsTableBody');
                        if (data.empty) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-secondary">
                                        Data tidak ditemukan
                                    </td>
                                </tr>
                            `;
                        } else {
                            tbody.innerHTML = data.html;
                        }
                        lucide.createIcons();
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                    });
            }

            /* ==============================
               MODAL
            ============================== */

            function openModalAnnouncement() {

                const modal = document.getElementById("modalAnnouncement");
                const content = document.getElementById("modalContentAdd");

                modal.classList.remove("hidden");
                modal.classList.add("flex");

                // trigger animation
                setTimeout(() => {
                    modal.classList.remove("opacity-0");
                    content.classList.remove("scale-95", "opacity-0");
                    content.classList.add("scale-100", "opacity-100");
                }, 10);

            }

            function closeModalAnnouncement() {

                const modal = document.getElementById("modalAnnouncement");
                const content = document.getElementById("modalContentAdd");

                // reverse animation
                modal.classList.add("opacity-0");
                content.classList.remove("scale-100", "opacity-100");
                content.classList.add("scale-95", "opacity-0");

                setTimeout(() => {
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                }, 300);

            }

            /* ==============================
               VIEW SWITCH
            ============================== */

            function switchView(view) {

                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                const viewMap = {
                    'list': 'view-announcements',
                };

                const targetId = viewMap[view] || 'view-announcements';
                const target = document.getElementById(targetId);

                if (target) {
                    target.classList.remove('hidden');
                }

            }

            /* ==============================
                   EDIT MODAL
                ============================== */

            function openEditModal(id, judul, konten, tgl, status) {

                const modal = document.getElementById("modalEditAnnouncement");
                const content = document.getElementById("modalContentEdit");

                // set data
                document.getElementById("editJudul").value = judul;
                document.getElementById("editKonten").value = konten;
                document.getElementById("editTgl").value = tgl;
                document.getElementById("editStatus").value = status;

                document.getElementById("formEditAnnouncement").action = `/admin/master/menu/info-edit/${id}`;

                modal.classList.remove("hidden");
                modal.classList.add("flex");

                setTimeout(() => {
                    modal.classList.remove("opacity-0");
                    content.classList.remove("scale-95", "opacity-0");
                    content.classList.add("scale-100", "opacity-100");
                }, 10);

            }

            function closeEditModal() {

                const modal = document.getElementById("modalEditAnnouncement");
                const content = document.getElementById("modalContentEdit");

                modal.classList.add("opacity-0");
                content.classList.remove("scale-100", "opacity-100");
                content.classList.add("scale-95", "opacity-0");

                setTimeout(() => {
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                }, 300);

            }

            /* ==============================
               DELETE FUNCTION
            ============================== */

            function deleteAnnouncement(id) {

                if (!confirm('Yakin ingin menghapus pengumuman ini?')) return;

                fetch(`/admin/master/menu/info-hapus/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {

                        showToast('Berhasil dihapus', 'success');

                        loadAnnouncements(); // reload table

                    })
                    .catch(err => {

                        console.error(err);
                        showToast('Gagal menghapus', 'error');

                    });

            }
        </script>
    @endpush

</x-view.layout.app>
