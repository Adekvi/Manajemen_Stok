@if (Auth::check() && Auth::user()->role === 'user')
    <script src="{{ asset('asset/js/notif.js') }}"></script>
@endif
<script src="{{ asset('asset/js/custom.js') }}"></script>
<script>
    // Header
    const button = document.getElementById("user-menu-button");
    const dropdown = document.getElementById("user-dropdown");

    button.addEventListener("click", (e) => {
        e.stopPropagation();

        dropdown.classList.toggle("opacity-0");
        dropdown.classList.toggle("scale-95");
        dropdown.classList.toggle("pointer-events-none");
    });

    document.addEventListener("click", () => {
        dropdown.classList.add("opacity-0", "scale-95", "pointer-events-none");
    });

    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // =========== Accordion sederhana (untuk Food Menu dll) ===========
        document.querySelectorAll('[data-accordion]').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-accordion');
                const content = document.getElementById(targetId);
                if (!content) return;

                const isOpen = !content.classList.contains('hidden');
                // tutup semua accordion lain (opsional)
                document.querySelectorAll('[id^="food-menu"], [id^="other-accordion"]').forEach(
                    el => {
                        if (el !== content) el.classList.add('hidden');
                    });

                content.classList.toggle('hidden');
                // rotate icon (opsional)
                const chevron = this.querySelector('i[data-lucide="chevron-down"]');
                if (chevron) {
                    chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        });
    });

    let chartsInitialized = false;

    const originalInit = window.initializeCharts;
    if (originalInit) {
        window.initializeCharts = function() {
            if (chartsInitialized) {
                console.warn('initializeCharts sudah dipanggil sebelumnya, di-skip.');
                return;
            }
            chartsInitialized = true;
            originalInit();
        };
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    function closePageNotFoundModal() {
        document.getElementById('page-not-found-modal').classList.add('hidden');
    }

    function openSearchModal() {
        document.getElementById('search-modal').classList.remove('hidden');
        document.getElementById('search-modal').classList.add('flex');
        document.getElementById('global-search-input').focus();
    }

    function closeSearchModal() {
        document.getElementById('search-modal').classList.add('hidden');
        document.getElementById('search-modal').classList.remove('flex');
    }

    let searchTimeout = null;
    let currentController = null;

    function handleGlobalSearch(val) {
        const container = document.getElementById('search-results');

        if (!val || val.length < 2) {
            container.innerHTML = `
                <div class="text-center py-8 text-secondary text-sm">
                    Ketik minimal 2 karakter...
                </div>`;
            return;
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            doSearch(val);
        }, 280); // sedikit lebih cepat dari 300ms
    }

    async function doSearch(val) {
        const container = document.getElementById('search-results');

        // Cancel request sebelumnya
        if (currentController) {
            currentController.abort();
        }
        currentController = new AbortController();

        // Tampilkan loading
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center h-40 w-full gap-2 text-secondary">
                <i data-lucide="loader" class="size-6 animate-spin text-primary"></i>
                <span class="text-xs">Mencari data...</span>
            </div>
        `;
        lucide.createIcons();

        try {
            const res = await fetch(`/menu/search/global?q=${encodeURIComponent(val)}`, {
                signal: currentController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) throw new Error('Server error');

            const data = await res.json();

            if (!data.success || !data.data || data.data.length === 0) {
                container.innerHTML = `
                <div class="text-center py-8 text-secondary text-sm">
                    Tidak ada hasil ditemukan
                </div>`;
                return;
            }

            const colorMap = {
                produk: 'text-blue-500 bg-blue-500/10',
                stok_masuk: 'text-green-500 bg-green-500/10',
                stok_keluar: 'text-orange-500 bg-orange-500/10',
            };

            let html = '';
            data.data.forEach(item => {
                const color = colorMap[item.type] || 'text-primary bg-primary/10';

                html += `
                <div onclick="handleSearchResultClick('${item.type}', ${item.id})"
                     class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted cursor-pointer transition-colors">
                    <div class="size-10 ${color} rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="${item.icon}" class="size-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm truncate">${highlight(item.title)}</p>
                        <p class="text-xs text-secondary truncate">${item.subtitle}</p>
                        ${item.meta ? `<p class="text-xs text-secondary mt-1 truncate">${item.meta}</p>` : ''}
                    </div>
                </div>`;
            });

            container.innerHTML = html;
            lucide.createIcons();

        } catch (err) {
            if (err.name !== 'AbortError') {
                container.innerHTML = `
                <div class="text-center py-8 text-error text-sm">
                    Terjadi kesalahan saat mencari
                </div>`;
                console.error(err);
            }
        }
    }

    function highlight(text) {
        const input = document.getElementById('global-search-input')?.value.trim() || '';
        if (!input) return text;

        // Escape special regex characters
        const escaped = input.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`(${escaped})`, 'gi');
        return text.replace(regex, '<span class="text-primary font-semibold">$1</span>');
    }
</script>
