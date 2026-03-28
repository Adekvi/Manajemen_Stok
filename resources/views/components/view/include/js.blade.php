<script src="{{ asset('asset/js/notif.js') }}"></script>
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

        // =========== Inisialisasi chart (tetap) ===========
        initializeCharts();
    });

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

    function handleGlobalSearch(val) {
        const container = document.getElementById('search-results');
        if (!val) {
            container.innerHTML = '<div class="text-center py-8 text-secondary text-sm">Ketik sesuatu...</div>';
            return;
        }

        // Dummy results based on input
        container.innerHTML = `
            <div onclick="closeSearchModal(); switchPage('rooms', document.querySelectorAll('.nav-item')[1])" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted transition-all cursor-pointer">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center"><i data-lucide="bed-double" class="size-5 text-primary"></i></div>
            <div class="flex-1"><p class="font-medium">Kamar ${val}</p><p class="text-xs text-secondary">Lihat detail kamar</p></div>
            </div>
            <div onclick="closeSearchModal(); switchPage('tenants', document.querySelectorAll('.nav-item')[2])" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted transition-all cursor-pointer">
            <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center"><i data-lucide="user" class="size-5 text-success"></i></div>
            <div class="flex-1"><p class="font-medium">Penyewa "${val}"</p><p class="text-xs text-secondary">Cari di data penyewa</p></div>
            </div>
        `;
        lucide.createIcons();
    }
</script>
