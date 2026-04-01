// STATISTIK
// Data dari Laravel
;(function () {
    const {
        attendanceLabels = [],
        attendanceData = [],
        productionData = []
    } = window.dashboardData || {}

    function initializeCharts() {
        /* ==============================
                   LINE CHART (STOK KELUAR USER)
                ============================== */
        console.log('Initializing charts...')
        console.log('dashboardData:', window.dashboardData)

        const attEl = document.getElementById('attendanceChart')

        console.log('attendanceChart element:', attEl)

        if (attEl) {
            const safeData = attendanceData.length
                ? attendanceData
                : new Array(7).fill(0)

            new Chart(attEl, {
                type: 'line',
                data: {
                    labels: attendanceLabels.length
                        ? attendanceLabels
                        : ['-', '-', '-', '-', '-', '-', '-'],
                    datasets: [
                        {
                            label: 'Transaksi',
                            data: safeData,
                            borderColor: '#165DFF',
                            backgroundColor: 'rgba(22, 93, 255, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Transaksi: ' + context.parsed
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...safeData, 5) + 2,
                            ticks: {
                                callback: function (value) {
                                    return value // 🔥 bukan persen
                                }
                            }
                        }
                    }
                }
            })
        }

        /* ==============================
                   DOUGHNUT CHART (STATUS)
                ============================== */
        const prodEl = document.getElementById('productionChart')

        console.log('productionChart element:', prodEl)

        if (prodEl) {
            const total = productionData.reduce((a, b) => a + b, 0)

            new Chart(prodEl, {
                type: 'doughnut',
                data: {
                    labels: ['Draft', 'Posted', 'Cancelled'],
                    datasets: [
                        {
                            data: productionData,
                            backgroundColor: ['#6B7280', '#30B22D', '#EF4444'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.parsed
                                    const percent = total
                                        ? Math.round((value / total) * 100)
                                        : 0
                                    return `${context.label}: ${value} (${percent}%)`
                                }
                            }
                        }
                    }
                }
            })
        }
    }

    // AUTO RELOAD
    let lastHTML = ''

    function loadActivity() {
        const table = document.getElementById('activityTable')

        // 🔥 stop kalau tidak ada table
        if (!table) return

        const url = table.dataset.url

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then((res) => res.json())
            .then((data) => {
                if (!data.html) return

                // 🔥 update hanya jika berubah
                if (lastHTML !== data.html) {
                    table.innerHTML = data.html

                    animateRows()

                    lastHTML = data.html
                }

                // 🔥 re-init lucide icon
                if (window.lucide) {
                    lucide.createIcons()
                }
            })
            .catch((err) => console.error('Auto refresh error:', err))
    }

    /* ==============================
               ANIMASI
            ============================== */
    function animateRows() {
        const rows = document.querySelectorAll('#activityTable tr')

        rows.forEach((row, index) => {
            row.style.opacity = 0
            row.style.transform = 'translateY(10px)'

            setTimeout(() => {
                row.style.transition = 'all 0.3s ease'
                row.style.opacity = 1
                row.style.transform = 'translateY(0)'
            }, index * 40)
        })
    }

    /* ==============================
               AUTO REFRESH (SMART)
            ============================== */
    let interval = null

    function startAutoRefresh() {
        if (interval) clearInterval(interval)

        interval = setInterval(() => {
            loadActivity()
        }, 10000)
    }

    /* ==============================
               INIT
            ============================== */
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi Chart
        if (typeof initializeCharts === 'function') {
            initializeCharts()
        }

        // Activity table
        loadActivity()
        startAutoRefresh()
    })

    /* ==============================
               STOP SAAT TAB TIDAK AKTIF
            ============================== */
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            clearInterval(interval)
        } else {
            startAutoRefresh()
        }
    })
})()
