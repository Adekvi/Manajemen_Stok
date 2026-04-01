const notifBtn = document.getElementById('notif-button')
const notifDropdown = document.getElementById('notif-dropdown')
const notifList = document.getElementById('notif-list')
const notifCount = document.getElementById('notif-count')
const csrf = document.querySelector('meta[name="csrf-token"]').content

const notifPanel = document.getElementById('notif-panel')
const notifDetail = document.getElementById('notif-detail')

// =======================
// HELPER
// =======================
function openDropdown() {
    notifDropdown.classList.remove(
        'opacity-0',
        'scale-95',
        'pointer-events-none'
    )
}

function closeDropdown() {
    notifDropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none')
}

// =======================
// TOGGLE DROPDOWN
// =======================
if (notifBtn && notifDropdown) {
    notifBtn.addEventListener('click', (e) => {
        e.stopPropagation()
        notifDropdown.classList.toggle('opacity-0')
        notifDropdown.classList.toggle('scale-95')
        notifDropdown.classList.toggle('pointer-events-none')
    })

    document.addEventListener('click', (e) => {
        if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
            closeDropdown()
        }
    })
}

// =======================
// ALL NOTIF
// =======================
async function loadPanelNotif() {
    try {
        const res = await fetch('/user/info/notifikasi/all', {
            credentials: 'same-origin'
        })
        const data = await res.json()

        const panelList = document.getElementById('panel-list')
        if (!panelList) return

        panelList.innerHTML = ''

        if (data.length === 0) {
            panelList.innerHTML = `
                <div class="px-4 py-6 text-center text-xs text-secondary">
                    Tidak ada notifikasi
                </div>
            `
            return
        }

        data.forEach((n) => {
            panelList.innerHTML += `
                <div class="panel-item px-4 py-4 border-b border-border cursor-pointer hover:bg-muted ${!n.is_read ? 'bg-primary/5' : ''}"
                     data-id="${n.id}"
                     data-judul="${n.judul}"
                     data-deskripsi="${n.deskripsi}"
                     data-tgl="${n.waktu}">

                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold text-foreground">
                            ${n.judul}
                        </p>

                        ${
                            !n.is_read
                                ? `
                                    <span class="flex items-center gap-1 text-[10px] font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                                        Baru
                                    </span>
                                    `
                                : ''
                        }

                    </div>

                    <p class="text-xs text-secondary mt-1">
                        ${(n.deskripsi || '').substring(0, 100)}
                    </p>

                    <span class="text-[10px] text-secondary">
                        ${n.waktu}
                    </span>
                </div>
            `
        })
    } catch (err) {
        console.error(err)
    }
}

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
                <div class="notif-item px-4 py-3 hover:bg-muted cursor-pointer transition border-b border-border/50"
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

        // badge
        notifCount.innerText = data.length
        notifCount.classList.remove('hidden')

        // animasi kecil
        notifCount.classList.add('scale-125')
        setTimeout(() => {
            notifCount.classList.remove('scale-125')
        }, 150)
    } catch (err) {
        console.error('Notif error:', err)
    }
}

// =======================
// MARK ALL
// =======================
document.getElementById('mark-all')?.addEventListener('click', async (e) => {
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
        loadPanelNotif()
    } catch (err) {
        console.error(err)
    }
})

// =======================
// LIHAT SEMUA (🔥 FIX UTAMA)
// =======================
document.getElementById('lihat-semua')?.addEventListener('click', (e) => {
    e.stopPropagation()

    closeDropdown()

    notifPanel?.classList.remove('translate-x-full')

    // 🔥 load full notif saat panel dibuka
    loadPanelNotif()
})

// =======================
// CLICK ITEM (dropdown)
// =======================
document.addEventListener('click', async (e) => {
    const item = e.target.closest('.notif-item')

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

        // tampilkan detail
        document.getElementById('detail-judul').innerText = item.dataset.judul
        document.getElementById('detail-deskripsi').innerText =
            item.dataset.deskripsi
        document.getElementById('detail-tgl').innerText = item.dataset.tgl

        notifDetail.classList.remove('hidden')
        notifDetail.classList.add('flex')

        // optional: tutup dropdown biar fokus ke detail
        closeDropdown()

        loadNotif()
        loadPanelNotif()
    } catch (err) {
        console.error(err)
    }
})

// =======================
// CLICK ITEM (panel)
// =======================
document.addEventListener('click', async (e) => {
    const item = e.target.closest('.panel-item')

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

        // tampil detail
        document.getElementById('detail-judul').innerText = item.dataset.judul
        document.getElementById('detail-deskripsi').innerText =
            item.dataset.deskripsi
        document.getElementById('detail-tgl').innerText = item.dataset.tgl

        notifDetail.classList.remove('hidden')
        notifDetail.classList.add('flex')

        // refresh semua
        loadNotif()
        loadPanelNotif()
    } catch (err) {
        console.error(err)
    }
})

// =======================
// CLOSE PANEL
// =======================
function closeNotifPanel() {
    notifPanel?.classList.add('translate-x-full')
}

// =======================
// CLOSE DETAIL
// =======================
function closeDetail() {
    notifDetail?.classList.add('hidden')
}

// =======================
// INIT
// =======================
loadNotif()
loadPanelNotif()
setInterval(loadNotif, 10000)
