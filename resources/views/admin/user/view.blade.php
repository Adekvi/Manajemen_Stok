<x-view.layout.app title="Pengguna">

    <div id="view-users" class="page-section">

        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Pengguna</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="relative flex-1 max-w-md">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                <input type="text" id="userSearch" placeholder="Cari username atau email..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                    onkeyup="searchUsers(this.value)">
            </div>
            <div class="flex gap-3">
                <button onclick="openAddUserModal()"
                    class="bg-primary hover:bg-primary-hover text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 shadow-lg shadow-primary/20 transition-all cursor-pointer">
                    <i data-lucide="plus" class="size-5"></i>
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
                            <th class="p-4 font-semibold text-sm text-secondary">Hak Akses</th>
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

    <!-- MODAL TAMBAH PENGGUNA -->
    <div id="modalAddUser"
        class="fixed inset-0 z-100 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-all duration-300">

        <div id="modalContentAdd"
            class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-border overflow-hidden 
                scale-95 opacity-0 transition-all duration-300 ease-out">

            <!-- Header -->
            <div class="p-6 border-b border-border flex justify-between items-center">
                <h3 class="font-semibold text-xl">Tambah Pengguna Baru</h3>
                <button onclick="closeAddUserModal()" class="p-2 rounded-xl hover:bg-muted transition-colors">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="formAddUser" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-8">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="username" name="username" required
                            class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none transition-all text-sm"
                            placeholder="Nama pengguna">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none transition-all text-sm"
                            placeholder="email@domain.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>

                        <div class="relative group">
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none transition-all text-sm pr-12"
                                placeholder="Minimal 3 karakter">

                            <button type="button" id="toggle-password-btn"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary hover:text-foreground transition-all p-1.5 rounded-xl hover:bg-muted">
                                <i id="eye-icon" data-lucide="eye-off" class="size-5"></i>
                            </button>
                        </div>

                        <p class="text-xs text-secondary mt-1.5">Minimal 3 karakter</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Role</label>
                        <div class="relative">
                            <div class="px-4 py-3.5 bg-muted/50 rounded-2xl text-sm font-medium text-foreground">
                                Staff (User)
                            </div>
                            <input type="hidden" name="role" value="user">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Hak Akses</label>
                        <div class="relative">
                            <select id="is_active" name="is_active" required
                                class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none appearance-none cursor-pointer text-sm">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                            <i data-lucide="chevron-down"
                                class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-5 border-t border-border bg-muted/30 rounded-b-3xl">
                <button onclick="closeAddUserModal()"
                    class="px-6 py-2.5 rounded-2xl border border-border text-sm font-medium hover:bg-muted transition">
                    Batal
                </button>
                <button onclick="submitAddUser()"
                    class="px-6 py-2.5 rounded-2xl cursor-pointer bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition flex items-center gap-2">
                    <i data-lucide="save" class="size-4"></i>
                    Simpan Pengguna
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL UBAH STATUS USER -->
    <div id="status-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">

        <div id="status-modal-card"
            class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-border overflow-hidden scale-95 opacity-0 transition-all duration-300 ease-out">

            <!-- HEADER -->
            <div class="p-6 border-b border-border flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Ubah Hak Akses User</h3>
                    <p class="text-sm text-secondary mt-1">Aktif = boleh login | Nonaktif = tidak boleh login</p>
                </div>
                <button onclick="closeStatusModal()" class="p-2 rounded-xl hover:bg-muted transition-colors">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="p-6 space-y-6">
                <input type="hidden" id="user-id">

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Username</label>
                    <div id="user-name-display"
                        class="px-4 py-3.5 bg-muted/50 rounded-2xl font-medium text-foreground">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Hak Akses</label>
                    <div class="relative">
                        <select id="status-select"
                            class="w-full px-4 py-3.5 rounded-2xl border border-border bg-white focus:ring-2 focus:ring-primary/30 outline-none appearance-none cursor-pointer text-sm">
                            <option value="1">Aktif (Boleh Login)</option>
                            <option value="0">Nonaktif (Tidak Boleh Login)</option>
                        </select>
                        <i data-lucide="chevron-down"
                            class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="p-6 border-t border-border bg-muted/30 flex gap-3">
                <button onclick="closeStatusModal()"
                    class="flex-1 py-3 rounded-2xl border border-border text-sm font-medium hover:bg-muted transition">
                    Batal
                </button>
                <button onclick="updateUserStatus()"
                    class="flex-1 py-3 rounded-2xl cursor-pointer bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PENGGUNA --}}
    <div id="modalEditUser"
        class="fixed inset-0 z-100 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-all duration-300">

        <div id="modalContentEdit"
            class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-border overflow-hidden 
                scale-95 opacity-0 transition-all duration-300 ease-out">

            <form id="formEditUser" method="POST">
                @csrf
                @method('PUT')

                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <i data-lucide="edit-3" class="size-5 text-primary"></i>
                        Edit Pengguna
                    </h2>
                    <button type="button" onclick="closeEditModal()"
                        class="p-2 rounded-xl hover:bg-muted transition-colors">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="p-6">
                    <input type="hidden" id="edit_user_id" name="id">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-8">

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="edit_username" name="username" required
                                class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Email</label>
                            <input type="email" id="edit_email" name="email"
                                class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Password (kosongkan jika tidak
                                diubah)</label>
                            <div class="relative group">
                                <input type="password" id="edit_password" name="password"
                                    class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none transition-all text-sm pr-12"
                                    placeholder="Minimal 3 karakter">

                                <button type="button" id="toggle-edit-password-btn"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary hover:text-foreground transition-all p-1.5 rounded-xl hover:bg-muted">
                                    <i id="eye-icon-edit" data-lucide="eye-off" class="size-5"></i>
                                </button>
                            </div>
                            <p class="text-xs text-secondary mt-1.5">Kosongkan jika password tidak ingin diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Role</label>
                            <div class="relative">
                                <div class="px-4 py-3.5 bg-muted/50 rounded-2xl text-sm font-medium text-foreground">
                                    Staff (User)
                                </div>
                                <input type="hidden" id="edit_role" name="role" value="user">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Hak Akses</label>
                            <div class="relative">
                                <select id="edit_is_active" name="is_active" required
                                    class="w-full px-4 py-3.5 rounded-2xl border border-border bg-muted/30 focus:ring-2 focus:ring-primary/30 outline-none appearance-none cursor-pointer text-sm">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                                <i data-lucide="chevron-down"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex justify-end gap-3 px-6 py-5 border-t border-border bg-muted/30 rounded-b-3xl">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2.5 rounded-2xl border border-border text-sm font-medium hover:bg-muted transition">
                        Batal
                    </button>
                    <button type="button" onclick="submitEditUser()"
                        class="px-6 py-2.5 rounded-2xl bg-primary cursor-pointer text-white text-sm font-semibold hover:bg-primary/90 transition flex items-center gap-2">
                        <i data-lucide="save" class="size-4"></i>
                        Update Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            function switchView(view) {
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
                const viewMap = {
                    'list': 'view-users'
                };
                const target = document.getElementById(viewMap[view] || 'view-users');
                if (target) target.classList.remove('hidden');
            }

            // USER SEARCH - Optimized
            let searchTimer;

            function searchUsers(keyword) {
                fetch(`/admin/pengguna/view?search=${encodeURIComponent(keyword)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        document.getElementById('usersTableBody').innerHTML = res.html;
                    })
                    .catch(err => console.error(err));
            }

            // ==================== MODAL TAMBAH USER ====================
            async function submitAddUser() {
                const button = event.currentTarget;
                const originalHTML = button.innerHTML;
                button.innerHTML = `<i data-lucide="loader" class="size-4 animate-spin"></i> Menyimpan...`;
                button.disabled = true;

                const formData = new FormData(document.getElementById('formAddUser'));

                try {
                    const res = await fetch('/admin/pengguna/store', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });

                    const data = await res.json();

                    if (data.success) {
                        alert(data.message);
                        closeAddUserModal();
                        location.reload(); // atau refresh table via AJAX
                    } else {
                        alert(data.message || 'Gagal menambahkan pengguna');
                    }
                } catch (err) {
                    alert('Terjadi kesalahan saat menyimpan');
                } finally {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
            }

            function openAddUserModal() {
                const modal = document.getElementById('modalAddUser');
                const content = document.getElementById('modalContentAdd');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                void modal.offsetWidth;

                modal.style.opacity = '1';
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');

                document.getElementById('formAddUser').reset();
                lucide.createIcons();
            }

            function closeAddUserModal() {
                const modal = document.getElementById('modalAddUser');
                const content = document.getElementById('modalContentAdd');

                modal.style.opacity = '0';
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');
                }, 280);
            }

            // ==================== MODAL EDIT USER ====================
            function editData(id) {
                fetch(`/admin/pengguna/show/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('edit_user_id').value = data.id;
                        document.getElementById('edit_username').value = data.username || '';
                        document.getElementById('edit_email').value = data.email || '';
                        document.getElementById('edit_is_active').value = data.is_active ? "1" : "0";
                        document.getElementById('edit_password').value = '';

                        const form = document.getElementById('formEditUser');
                        form.dataset.userId = id;

                        openEditModal();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memuat data untuk diedit');
                    });
            }

            function openEditModal() {
                const modal = document.getElementById('modalEditUser');
                const content = document.getElementById('modalContentEdit');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                void modal.offsetWidth;

                modal.style.opacity = '1';
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');

                lucide.createIcons();
            }

            function closeEditModal() {
                const modal = document.getElementById('modalEditUser');
                const content = document.getElementById('modalContentEdit');

                modal.style.opacity = '0';
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 280);
            }

            async function submitEditUser() {
                const button = event.currentTarget;
                const originalHTML = button.innerHTML;

                button.innerHTML = `<i data-lucide="loader" class="size-4 animate-spin"></i> Updating...`;
                button.disabled = true;

                const form = document.getElementById('formEditUser');
                const userId = form.dataset.userId;

                if (!userId) {
                    alert('ID pengguna tidak ditemukan');
                    resetButton(button, originalHTML);
                    return;
                }

                const formData = new FormData(form);

                try {
                    const res = await fetch(`/admin/pengguna/edit/${userId}`, {
                        method: 'POST', // Laravel menerima PUT via POST + _method
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });

                    const data = await res.json();

                    if (data.success) {
                        alert(data.message || 'Pengguna berhasil diperbarui');
                        closeEditModal();
                        location.reload(); // nanti bisa diganti dengan refresh partial table
                    } else {
                        alert(data.message || 'Gagal memperbarui pengguna');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan saat menyimpan perubahan');
                } finally {
                    resetButton(button, originalHTML);
                }
            }

            // Helper function
            function resetButton(button, originalHTML) {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }

            // ==================== PASSWORD TOGGLE - TAMBAH USER ====================
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');

                const isVisible = passwordInput.type === 'text';

                if (isVisible) {
                    passwordInput.type = 'password';
                    eyeIcon.setAttribute('data-lucide', 'eye-off');
                } else {
                    passwordInput.type = 'text';
                    eyeIcon.setAttribute('data-lucide', 'eye');
                }

                lucide.createIcons();
            }

            // ==================== PASSWORD TOGGLE - EDIT USER ====================
            function toggleEditPasswordVisibility() {
                const passwordInput = document.getElementById('edit_password');
                const eyeIcon = document.getElementById('eye-icon-edit');

                const isVisible = passwordInput.type === 'text';

                if (isVisible) {
                    passwordInput.type = 'password';
                    eyeIcon.setAttribute('data-lucide', 'eye-off');
                } else {
                    passwordInput.type = 'text';
                    eyeIcon.setAttribute('data-lucide', 'eye');
                }

                lucide.createIcons();
            }

            // ==================== MODAL UBAH STATUS USER ====================
            let currentUserId = null;

            function openStatusModal(userId, currentStatus, username) {
                currentUserId = userId;

                document.getElementById('user-id').value = userId;
                document.getElementById('status-select').value = currentStatus ? "1" : "0";
                document.getElementById('user-name-display').textContent = username || 'User tidak ditemukan';

                const modal = document.getElementById('status-modal');
                const card = document.getElementById('status-modal-card');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                void modal.offsetWidth;

                modal.style.opacity = '1';
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');

                lucide.createIcons();
            }

            function closeStatusModal() {
                const modal = document.getElementById('status-modal');
                const card = document.getElementById('status-modal-card');

                modal.style.opacity = '0';
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 280);
            }

            async function updateUserStatus() {
                const userId = currentUserId;
                const newStatus = document.getElementById('status-select').value;

                if (!userId) return;

                const button = event.currentTarget;
                const originalText = button.innerHTML;
                button.innerHTML = `<i data-lucide="loader" class="size-4 animate-spin"></i> Menyimpan...`;
                button.disabled = true;

                try {
                    const res = await fetch(`/admin/pengguna/status/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            is_active: newStatus
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        alert(data.message);
                        closeStatusModal();
                        location.reload(); // nanti bisa diganti dengan AJAX refresh table
                    } else {
                        alert(data.message || 'Gagal mengubah status');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan saat mengubah hak akses');
                } finally {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }

            // ==================== INITIALIZATION ====================
            document.addEventListener('DOMContentLoaded', () => {
                lucide.createIcons();

                // Event listener untuk toggle password di modal Tambah
                const toggleBtnAdd = document.getElementById('toggle-password-btn');
                if (toggleBtnAdd) {
                    toggleBtnAdd.addEventListener('click', togglePasswordVisibility);
                }

                // Event listener untuk toggle password di modal Edit
                const toggleBtnEdit = document.getElementById('toggle-edit-password-btn');
                if (toggleBtnEdit) {
                    toggleBtnEdit.addEventListener('click', toggleEditPasswordVisibility);
                }
            });
        </script>
    @endpush
</x-view.layout.app>
