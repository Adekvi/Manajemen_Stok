document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons()

    // ELEMENTS
    const toggleBtn = document.getElementById('theme-toggle-btn')
    const panel = document.getElementById('theme-panel')
    const closeBtn = document.getElementById('close-theme-panel')
    const fontSelect = document.getElementById('font-family-select')
    const resetBtn = document.getElementById('reset-all-settings')

    // Toggle panel
    function togglePanel() {
        panel.classList.toggle('translate-x-full')
        document.body.classList.toggle('panel-open')
    }

    toggleBtn.addEventListener('click', togglePanel)
    closeBtn.addEventListener('click', togglePanel)

    // Tutup dengan ESC
    document.addEventListener('keydown', (e) => {
        if (
            e.key === 'Escape' &&
            !panel.classList.contains('translate-x-full')
        ) {
            togglePanel()
        }
    })

    // Ganti warna utama
    document.querySelectorAll('.color-option').forEach((btn) => {
        btn.addEventListener('click', () => {
            const color = btn.dataset.color
            const hover = adjustColor(color, -18) // buat hover lebih gelap

            document.documentElement.style.setProperty('--primary', color)
            document.documentElement.style.setProperty('--primary-hover', hover)

            // Highlight tombol yang dipilih
            document
                .querySelectorAll('.color-option')
                .forEach((b) =>
                    b.classList.remove(
                        'ring-2',
                        'ring-offset-2',
                        'ring-gray-300'
                    )
                )
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-gray-300')

            localStorage.setItem('custom-primary', color)
        })
    })

    // Mode light/dark
    document.querySelectorAll('.mode-option').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mode = btn.dataset.mode
            if (mode === 'dark') {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
            localStorage.setItem('color-mode', mode)

            // Highlight tombol mode
            document
                .querySelectorAll('.mode-option')
                .forEach((b) => b.classList.remove('bg-primary', 'text-white'))
            btn.classList.add('bg-primary', 'text-white')
        })
    })

    // Ganti font
    fontSelect.addEventListener('change', (e) => {
        const font = e.target.value
        document.documentElement.style.setProperty('--font-sans', font)
        localStorage.setItem('custom-font', font)
    })

    // Reset
    resetBtn.addEventListener('click', () => {
        localStorage.clear()
        location.reload()
    })

    // Load saved settings
    const savedPrimary = localStorage.getItem('custom-primary')
    if (savedPrimary) {
        const hover = adjustColor(savedPrimary, -18)
        document.documentElement.style.setProperty('--primary', savedPrimary)
        document.documentElement.style.setProperty('--primary-hover', hover)
        document
            .querySelector(`[data-color="${savedPrimary}"]`)
            ?.classList.add('ring-2', 'ring-offset-2', 'ring-gray-300')
    }

    const savedMode = localStorage.getItem('color-mode') || 'light'
    if (savedMode === 'dark') {
        document.documentElement.classList.add('dark')
        document
            .querySelector('[data-mode="dark"]')
            ?.classList.add('bg-primary', 'text-white')
    } else {
        document
            .querySelector('[data-mode="light"]')
            ?.classList.add('bg-primary', 'text-white')
    }

    const savedFont =
        localStorage.getItem('custom-font') ||
        "'Lexend Deca', system-ui, sans-serif"
    document.documentElement.style.setProperty('--font-sans', savedFont)
    if (fontSelect) fontSelect.value = savedFont

    // Fungsi helper untuk menghitung warna hover
    function adjustColor(hex, percent) {
        let r = parseInt(hex.slice(1, 3), 16)
        let g = parseInt(hex.slice(3, 5), 16)
        let b = parseInt(hex.slice(5, 7), 16)
        r = Math.max(0, Math.min(255, r + (percent * r) / 100))
        g = Math.max(0, Math.min(255, g + (percent * g) / 100))
        b = Math.max(0, Math.min(255, b + (percent * b) / 100))
        return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`
    }
})
