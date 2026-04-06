document.addEventListener('DOMContentLoaded', () => {
    const notifBtn = document.getElementById('notif-button')
    const notifDropdown = document.getElementById('notif-dropdown')
    const notifList = document.getElementById('notif-list')
    const notifCount = document.getElementById('notif-count')
    const notifPanel = document.getElementById('notif-panel')
    const notifDetail = document.getElementById('notif-detail')
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content

    if (!notifBtn || !notifDropdown) return

    // =======================
    // STATE
    // =======================
    let isOpen = false

    function openDropdown() {
        notifDropdown.classList.remove(
            'opacity-0',
            'scale-95',
            'pointer-events-none'
        )
        isOpen = true
    }

    function closeDropdown() {
        notifDropdown.classList.add(
            'opacity-0',
            'scale-95',
            'pointer-events-none'
        )
        isOpen = false
    }

    function toggleDropdown() {
        isOpen ? closeDropdown() : openDropdown()
    }

    // =======================
    // TOGGLE
    // =======================
    notifBtn.addEventListener('click', (e) => {
        e.stopPropagation()
        toggleDropdown()
    })

    // klik luar
    document.addEventListener('click', (e) => {
        if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
            closeDropdown()
        }
    })

    // =======================
    // FETCH NOTIF
    // =======================
    async function loadNotif() {
        try {
            const res = await fetch('/user/info/notifikasi', {
                credentials: 'same-origin'
            })

            const data = await res.json()

            if (!notifList || !notifCount) return

            notifList.innerHTML = ''

            if (data.length === 0) {
                notifList.innerHTML = `
                    <div class="px-4 py-6 text-center text-xs text-secondary">
                        Tidak ada notifikasi
                    </div>
                `
                notifCount.classList.add('hidden')
                return
            }

            data.forEach((n) => {
                notifList.innerHTML += `
                    <div class="notif-item px-4 py-3 hover:bg-muted cursor-pointer border-b border-border/50"
                        data-id="${n.id}"
                        data-judul="${n.judul}"
                        data-deskripsi="${n.deskripsi}"
                        data-tgl="${n.waktu}">
                        
                        <p class="text-sm font-medium text-foreground">
                            ${n.judul}
                        </p>

                        <p class="text-xs text-secondary">
                            ${(n.deskripsi || '').substring(0, 100)}
                        </p>

                        <span class="text-[10px] text-secondary">
                            ${n.waktu}
                        </span>
                    </div>
                `
            })

            notifCount.innerText = data.length
            notifCount.classList.remove('hidden')
        } catch (err) {
            console.error('Notif error:', err)
        }
    }

    // =======================
    // MARK ALL
    // =======================
    document
        .getElementById('mark-all')
        ?.addEventListener('click', async (e) => {
            e.stopPropagation()

            try {
                await fetch('/user/info/notifikasi/read-all', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    }
                })

                loadNotif()
            } catch (err) {
                console.error(err)
            }
        })

    // =======================
    // CLICK ITEM (DELEGATION)
    // =======================
    document.addEventListener('click', async (e) => {
        const item = e.target.closest('.notif-item, .panel-item')
        if (!item) return

        const id = item.dataset.id

        try {
            await fetch(`/user/info/notifikasi/read/${id}`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrf
                }
            })

            document.getElementById('detail-judul').innerText =
                item.dataset.judul
            document.getElementById('detail-deskripsi').innerText =
                item.dataset.deskripsi
            document.getElementById('detail-tgl').innerText = item.dataset.tgl

            notifDetail?.classList.remove('hidden')
            notifDetail?.classList.add('flex')

            closeDropdown()
            loadNotif()
        } catch (err) {
            console.error(err)
        }
    })

    // =======================
    // INIT
    // =======================
    loadNotif()
    setInterval(loadNotif, 10000)
})
