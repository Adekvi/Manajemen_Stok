const notifBtn = document.getElementById('notif-button')
const notifDropdown = document.getElementById('notif-dropdown')

if (notifBtn && notifDropdown) {
    notifBtn.addEventListener('click', function (e) {
        e.stopPropagation()

        notifDropdown.classList.toggle('opacity-0')
        notifDropdown.classList.toggle('scale-95')
        notifDropdown.classList.toggle('pointer-events-none')
    })

    // klik luar = close
    document.addEventListener('click', function (e) {
        if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
            notifDropdown.classList.add(
                'opacity-0',
                'scale-95',
                'pointer-events-none'
            )
        }
    })
}

// update notif
async function loadNotif() {
    try {
        const res = await fetch('/api/notifikasi')
        const data = await res.json()

        const container = document.querySelector('#notif-dropdown .max-h-80')
        const badge = document.getElementById('notif-count')

        if (!container || !badge) return

        container.innerHTML = ''

        if (data.length === 0) {
            container.innerHTML = `<div class="px-4 py-6 text-center text-xs text-secondary">
                Tidak ada notifikasi
            </div>`
        } else {
            data.forEach((n) => {
                container.innerHTML += `
                    <div class="px-4 py-3 hover:bg-muted cursor-pointer transition">
                        <p class="text-sm font-medium text-foreground">${n.judul}</p>
                        <p class="text-xs text-secondary">${n.deskripsi.substring(0, 50)}</p>
                        <span class="text-[10px] text-secondary">${n.waktu}</span>
                    </div>
                `
            })
        }

        badge.innerText = data.length
    } catch (err) {
        console.error(err)
    }
}

// load pertama
loadNotif()

// auto refresh tiap 10 detik
setInterval(loadNotif, 10000)

// MARK ALL
document.getElementById('mark-all').addEventListener('click', () => {
    document.getElementById('notif-list').innerHTML = `
        <div class="px-4 py-6 text-center text-xs text-secondary">
            Semua notifikasi telah dibaca
        </div>
    `

    document.getElementById('notif-count').innerText = ''
})

// LIHAT SEMUA
document.getElementById('lihat-semua').addEventListener('click', () => {
    document.getElementById('notif-panel').classList.remove('translate-x-full')
})

// CLOSE PANEL
function closeNotifPanel() {
    document.getElementById('notif-panel').classList.add('translate-x-full')
}

// CLICK ITEM → DETAIL
document.addEventListener('click', function (e) {
    const item = e.target.closest('.notif-item, .panel-item')

    if (item) {
        document.getElementById('detail-judul').innerText = item.dataset.judul
        document.getElementById('detail-deskripsi').innerText =
            item.dataset.deskripsi
        document.getElementById('detail-tgl').innerText = item.dataset.tgl

        document.getElementById('notif-detail').classList.remove('hidden')
        document.getElementById('notif-detail').classList.add('flex')
    }
})

// CLOSE DETAIL
function closeDetail() {
    document.getElementById('notif-detail').classList.add('hidden')
}
