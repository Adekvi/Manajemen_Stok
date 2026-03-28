<x-view.layout.app title="Pengguna">

    <div id="view-users" class="page-section">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Pengguna</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="relative flex-1 max-w-md">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                <input type="text" id="userSearch" placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-border  focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                    oninput="filterUsers()">
            </div>
            <div class="flex gap-3">
                <select id="userRoleFilter" onchange="filterUsers()"
                    class=" border border-border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary/20 text-sm font-medium">
                    <option value="all">Semua Role</option>
                    <option value="owner">Owner</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
                <button
                    onclick="document.getElementById('add-user-modal').classList.remove('hidden'); document.getElementById('add-user-modal').classList.add('flex')"
                    class="bg-primary hover:bg-primary-hover text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 shadow-lg shadow-primary/20 transition-all cursor-pointer">
                    <i data-lucide="user-plus" class="size-5"></i>
                    <span class="hidden sm:inline">Tambah Pengguna</span>
                </button>
            </div>
        </div>

        <div class=" rounded-2xl border border-border overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="p-4 font-semibold text-sm text-secondary">Nama Pengguna</th>
                            <th class="p-4 font-semibold text-sm text-secondary">Email</th>
                            <th class="p-4 font-semibold text-sm text-secondary">Role</th>
                            <th class="p-4 font-semibold text-sm text-secondary">Status</th>
                            <th class="p-4 font-semibold text-sm text-secondary text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @include('admin.user.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="add-user-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
        <div class=" rounded-3xl w-full max-w-md shadow-2xl transform transition-all">
            <div class="p-6 border-b border-border flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Pengguna Baru</h3>
                <button
                    onclick="document.getElementById('add-user-modal').classList.add('hidden'); document.getElementById('add-user-modal').classList.remove('flex')"
                    class="text-secondary hover:text-foreground cursor-pointer"><i data-lucide="x"
                        class="size-6"></i></button>
            </div>
            <div class="p-6 flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Nama Lengkap</label>
                    <input type="text" id="inputUserName"
                        class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus: focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="Nama pengguna">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Email</label>
                    <input type="email" id="inputUserEmail"
                        class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus: focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="email@kosmanager.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Role</label>
                    <div class="relative">
                        <select id="selectUserRole"
                            class="w-full px-4 py-3 rounded-xl border border-border bg-muted/30 focus: focus:ring-2 focus:ring-primary/20 outline-none appearance-none cursor-pointer">
                            <option value="Staff">Staff</option>
                            <option value="Admin">Admin</option>
                            <option value="Owner">Owner</option>
                        </select>
                        <i data-lucide="chevron-down"
                            class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-border flex gap-3">
                <button
                    onclick="document.getElementById('add-user-modal').classList.add('hidden'); document.getElementById('add-user-modal').classList.remove('flex')"
                    class="flex-1 py-3 rounded-full border border-border font-semibold hover:bg-muted transition-colors cursor-pointer">Batal</button>
                <button onclick="confirmAddUser()"
                    class="flex-1 py-3 rounded-full bg-primary text-white font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all cursor-pointer">Simpan</button>
            </div>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            function switchView(view) {
                // Sembunyikan semua section
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                // Mapping view → id yang sebenarnya
                const viewMap = {
                    'list': 'view-users',
                };

                const targetId = viewMap[view] || 'view-users'; // fallback ke list
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
        </script>
    @endpush
</x-view.layout.app>
