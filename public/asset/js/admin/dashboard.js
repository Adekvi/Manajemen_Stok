// Data dari Laravel (pastikan format benar)
;(function () {
    const {
        attendanceLabels = [],
        attendanceData = [],
        productionData = []
    } = window.dashboardData || {}

    let attendanceChartInstance = null
    let productionChartInstance = null

    function initializeCharts() {
        const attEl = document.getElementById('attendanceChart')

        if (attEl) {
            if (attendanceChartInstance) {
                attendanceChartInstance.destroy() // 🔥 destroy dulu
            }

            let safeAttendanceData = Array.isArray(attendanceData)
                ? attendanceData
                : typeof attendanceData === 'object' && attendanceData !== null
                  ? Object.values(attendanceData)
                  : []

            if (safeAttendanceData.length === 0) {
                safeAttendanceData = new Array(7).fill(0)
            }

            attendanceChartInstance = new Chart(attEl, {
                type: 'line',
                data: {
                    labels: Array.isArray(attendanceLabels)
                        ? attendanceLabels
                        : new Array(7).fill('?'),
                    datasets: [
                        {
                            label: 'Transaksi',
                            data: safeAttendanceData,
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
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax:
                                safeAttendanceData.length > 0
                                    ? Math.max(...safeAttendanceData) * 1.1 ||
                                      100
                                    : 100,
                            ticks: {
                                callback: (v) => v + '%'
                            }
                        }
                    }
                }
            })
        }

        const prodEl = document.getElementById('productionChart')

        if (prodEl) {
            if (productionChartInstance) {
                productionChartInstance.destroy() // 🔥 destroy dulu
            }

            productionChartInstance = new Chart(prodEl, {
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
                                label: (ctx) => `${ctx.label}: ${ctx.parsed}%`
                            }
                        }
                    }
                }
            })
        }
    }

    // Jalankan setelah DOM siap
    document.addEventListener('DOMContentLoaded', () => {
        const hasChart =
            document.getElementById('attendanceChart') ||
            document.getElementById('productionChart')

        if (hasChart && typeof initializeCharts === 'function') {
            initializeCharts()
        }

        if (
            document.getElementById('activityTable') &&
            typeof loadActivity === 'function'
        ) {
            loadActivity()
            startAutoRefresh()
        }
    })

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
