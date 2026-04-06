<x-view.layout.app title="Akses Menu User">

    <div id="view-menu" class="view-section flex flex-col flex-1 h-full">

        {{-- BREADCRUMB --}}
        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">Menu Akses</span>
        </div>

        <div class="flex flex-col gap-6">

            {{-- HEADER --}}
            <div>
                <h1 class="text-xl font-semibold">Manajemen Akses Menu</h1>
                <p class="text-sm text-gray-500">Atur menu yang dapat diakses oleh user</p>
            </div>

            {{-- SELECT USER --}}
            <div class="bg-white border border-border rounded-2xl p-5 shadow-sm">

                <form method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">

                    <div class="w-full md:w-80">
                        <label class="text-sm font-medium mb-1 block">Pilih User</label>
                        <select name="user_id" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-xl border border-border focus:border-primary">
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ optional($selectedUser)->id == $u->id ? 'selected' : '' }}>
                                    {{ $u->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- INFO USER --}}
                    @if ($selectedUser)
                        <div class="text-sm text-gray-600">
                            Mengatur akses untuk:
                            <span class="font-semibold text-primary">
                                {{ $selectedUser->username }}
                            </span>
                        </div>
                    @endif

                </form>

            </div>

            @if ($selectedUser)

                {{-- SEARCH --}}
                <div class="relative w-full md:w-80">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                    <input type="text" id="menuSearch" placeholder="Cari menu..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-border">
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('admin.menu.update', $selectedUser->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pb-16">

                        @foreach ($menus as $group => $groupMenus)
                            <div class="bg-white border border-border rounded-2xl p-4 shadow-sm">

                                {{-- GROUP HEADER --}}
                                <div class="flex justify-between items-center mb-3">
                                    <h2 class="font-semibold text-base">
                                        {{ $group ?? 'Lainnya' }}
                                    </h2>

                                    <button type="button" onclick="toggleGroup(this)"
                                        class="text-xs text-primary hover:underline">
                                        Pilih Semua
                                    </button>
                                </div>

                                {{-- MENU LIST --}}
                                <div class="space-y-2 menu-group">

                                    @foreach ($groupMenus as $menu)
                                        <label
                                            class="flex items-center gap-3 p-2 rounded-lg hover:bg-muted/40 transition menu-item">

                                            <input type="checkbox" name="menus[]" value="{{ $menu->id }}"
                                                class="size-4 accent-primary"
                                                {{ in_array($menu->id, $userMenus) ? 'checked' : '' }}>

                                            <div class="flex items-center gap-2">
                                                <i data-lucide="{{ $menu->icon ?? 'circle' }}"
                                                    class="size-4 text-secondary"></i>
                                                <span class="text-sm">{{ $menu->name }}</span>
                                            </div>

                                        </label>
                                    @endforeach

                                </div>

                            </div>
                        @endforeach

                    </div>

                    {{-- ACTION --}}
                    <div
                        class="sticky bottom-4 mt-6 bg-white border border-border rounded-2xl p-4 flex justify-between items-center shadow-sm">

                        <button type="button" onclick="checkAll()"
                            class="text-sm cursor-pointer text-primary hover:underline">
                            Centang Semua
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 rounded-2xl bg-primary cursor-pointer text-white text-sm font-semibold hover:bg-primary/90 transition flex items-center gap-2">
                            <i data-lucide="save" class="size-4"></i>
                            Simpan Akses
                        </button>

                    </div>

                </form>

            @endif

        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            lucide.createIcons();

            // SEARCH MENU
            document.getElementById('menuSearch')?.addEventListener('keyup', function() {
                let keyword = this.value.toLowerCase();

                document.querySelectorAll('.menu-item').forEach(item => {
                    let text = item.innerText.toLowerCase();
                    item.style.display = text.includes(keyword) ? '' : 'none';
                });
            });

            // TOGGLE GROUP
            function toggleGroup(button) {
                let group = button.closest('.bg-white');
                let checkboxes = group.querySelectorAll('input[type="checkbox"]');

                let allChecked = [...checkboxes].every(cb => cb.checked);

                checkboxes.forEach(cb => cb.checked = !allChecked);

                button.innerText = allChecked ? 'Pilih Semua' : 'Batal Pilih';
            }

            // CHECK ALL
            function checkAll() {
                let checkboxes = document.querySelectorAll('input[type="checkbox"]');
                let allChecked = [...checkboxes].every(cb => cb.checked);

                checkboxes.forEach(cb => cb.checked = !allChecked);
            }
        </script>
    @endpush

</x-view.layout.app>
