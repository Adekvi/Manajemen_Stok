/* ==============================
         MODAL FILTER
============================== */

function openFilterModal() {
    const modal = document.getElementById('modalFilter')
    const content = document.getElementById('modalFilterStyle')

    modal.classList.remove('hidden')
    modal.classList.add('flex')

    setTimeout(() => {
        modal.classList.remove('opacity-0')
        content.classList.remove('scale-95', 'opacity-0')
        content.classList.add('scale-100', 'opacity-100')
    }, 10)
}

function closeFilterModal() {
    const modal = document.getElementById('modalFilter')
    const content = document.getElementById('modalFilterStyle')

    modal.classList.add('opacity-0')
    content.classList.remove('scale-100', 'opacity-100')
    content.classList.add('scale-95', 'opacity-0')

    setTimeout(() => {
        modal.classList.add('hidden')
        modal.classList.remove('flex')
    }, 300)
}

/* ==============================
               QUICK FILTER
            ============================== */

function setQuickFilter(type) {
    // reset semua tombol
    document.querySelectorAll('.filter-chip').forEach((btn) => {
        btn.classList.remove('active')
    })
    // aktifkan yang dipilih
    const activeBtn = document.querySelector(`[data-type="${type}"]`)
    if (activeBtn) activeBtn.classList.add('active')

    const today = new Date()
    let start = new Date()
    let end = new Date()

    switch (type) {
        case 'hari':
            break

        case 'minggu':
            start.setDate(today.getDate() - 7)
            break

        case 'bulan':
            start.setMonth(today.getMonth() - 1)
            break

        case '3bulan':
            start.setMonth(today.getMonth() - 3)
            break

        case '6bulan':
            start.setMonth(today.getMonth() - 6)
            break

        case 'tahun':
            start.setFullYear(today.getFullYear() - 1)
            break
    }

    document.getElementById('start_date').value = formatDate(start)
    document.getElementById('end_date').value = formatDate(end)
}

/* ==============================
               FORMAT DATE
            ============================== */

function formatDate(date) {
    return date.toISOString().split('T')[0]
}

/* ==============================
               APPLY FILTER (SIAP BACKEND)
            ============================== */

function applyFilter() {
    const start = document.getElementById('start_date').value
    const end = document.getElementById('end_date').value

    const url = new URL(window.location.href)

    if (start) url.searchParams.set('start_date', start)
    if (end) url.searchParams.set('end_date', end)

    // 🔥 tampilkan info sebelum reload
    showFilterInfo(start, end)

    window.location.href = url.toString()
}

/* ==============================
                   SHOW FILTER INFO
                ============================== */

function showFilterInfo(start, end) {
    const wrapper = document.getElementById('filterInfoWrapper')
    const text = document.getElementById('filterText')

    if (!start || !end) {
        wrapper.classList.add('hidden')
        return
    }

    const startDate = formatDisplayDate(start)
    const endDate = formatDisplayDate(end)

    text.innerText = `Filter: ${startDate} - ${endDate}`

    wrapper.classList.remove('hidden')
}

/* ==============================
               FORMAT DISPLAY
            ============================== */

function formatDisplayDate(dateStr) {
    const date = new Date(dateStr)

    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

/* ==============================
               RESET FILTER
            ============================== */

function resetFilter() {
    document.getElementById('start_date').value = ''
    document.getElementById('end_date').value = ''
}

function clearFilter() {
    const url = new URL(window.location.href)

    url.searchParams.delete('start_date')
    url.searchParams.delete('end_date')

    window.location.href = url.toString()
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search)

    const start = params.get('start_date')
    const end = params.get('end_date')

    if (start && end) {
        showFilterInfo(start, end)
    }
})
