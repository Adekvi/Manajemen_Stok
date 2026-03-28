{{-- <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet"> --}}
<link
    href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&family=Inter:wght@100..900&family=Manrope:wght@200..800&family=Poppins:wght@100..900&family=Roboto:wght@100..900&display=swap"
    rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style type="text/tailwindcss">
    @theme inline {
        --color-primary: var(--primary);
        --color-primary-hover: var(--primary-hover);
        --color-foreground: var(--foreground);
        --color-secondary: var(--secondary);
        --color-muted: var(--muted);
        --color-border: var(--border);
        --color-card-grey: var(--card-grey);
        --color-card-message: var(--card-message);
        --color-accent-blue: var(--accent-blue);
        --color-accent-teal: var(--accent-teal);
        --color-accent-sky: var(--accent-sky);
        --color-success: var(--success);
        --color-success-light: var(--success-light);
        --color-success-dark: var(--success-dark);
        --color-error: var(--error);
        --color-error-light: var(--error-light);
        --color-error-lighter: var(--error-lighter);
        --color-error-dark: var(--error-dark);
        --color-warning: var(--warning);
        --color-warning-light: var(--warning-light);
        --color-warning-dark: var(--warning-dark);
        --color-info: var(--info);
        --color-info-light: var(--info-light);
        --color-info-dark: var(--info-dark);
        --color-alert: var(--alert);
        --color-alert-light: var(--alert-light);
        --color-alert-dark: var(--alert-dark);
        --color-gray-50: var(--gray-50);
        --color-gray-100: var(--gray-100);
        --color-gray-200: var(--gray-200);
        --color-gray-500: var(--gray-500);
        --color-gray-600: var(--gray-600);
        --color-gray-700: var(--gray-700);
        --font-sans: var(--font-sans);
        --radius-card: 24px;
        --radius-button: 50px;
        --radius-icon: 12px;
        --radius-xl: 16px;
        --radius-2xl: 20px;
        --radius-3xl: 24px;
    }

    :root {
        /* Custom */
        --sidebar-bg: #ffffff;
        --header-bg: #ffffff;
        --sidebar-text: #080C1A;
        --header-text: #080C1A;
        --nav-hover-bg: #EFF2F7;
        --nav-active-bg: rgba(22, 93, 255, 0.1);
        --help-card-bg: #ffffff;
        --body-bg: #ffffff;

        /* Transisi halus untuk semua elemen yang berubah warna */
        --transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease;

        --primary: #7cb13b;
        --primary-hover: #8fa25e;
        --foreground: #080C1A;
        --secondary: #6A7686;
        --muted: #EFF2F7;
        --border: #F3F4F3;
        --card-grey: #F1F3F6;
        --card-message: #C9E6FC;
        --accent-blue: #C9E6FC;
        --accent-teal: #82D9D7;
        --accent-sky: #DBEAFE;
        --success: #30B22D;
        --success-light: #DCFCE7;
        --success-dark: #166534;
        --error: #ED6B60;
        --error-light: #FEE2E2;
        --error-lighter: #FEF2F2;
        --error-dark: #991B1B;
        --warning: #FED71F;
        --warning-light: #FEF9C3;
        --warning-dark: #854D0E;
        --info: #165DFF;
        --info-light: #DBEAFE;
        --info-dark: #1E40AF;
        --alert: #F97316;
        --alert-light: #FFEDD5;
        --alert-dark: #9A3412;
        --gray-50: #F9FAFB;
        --gray-100: #F1F3F6;
        --gray-200: #E5E7EB;
        --gray-500: #6A7686;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --font-sans: 'Lexend Deca', sans-serif;
    }

    select {
        @apply appearance-none bg-no-repeat cursor-pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-position: right 10px center;
        padding-right: 40px;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    [data-theme="dark"],
    .dark {
        --body-bg: #111827;
        --sidebar-bg: #111827;
        --header-bg: #111827;
        --sidebar-text: #F3F4F6;
        --header-text: #F3F4F6;
        --foreground: #F3F4F6;
        --secondary: #9CA3AF;
        --muted: #1F2937;
        --border: #374151;
        --nav-hover-bg: #374151;
        --nav-active-bg: rgba(96, 165, 250, 0.2);
        --help-card-bg: #1F2937;
        --primary: #4CAF50;
        --primary-hover: #388E3C;
    }

    /* Terapkan transisi ke elemen kunci */
    #sidebar,
    header,
    .h-[90px],
    .nav-item>div,
    .border-border,
    .bg-white,
    .text-foreground,
    .text-secondary {
        transition: var(--transition);
    }

    /* Animasi gear */
    .animate-spin-slow {
        animation: spin-slow 18s linear infinite;
    }

    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    body.panel-open {
        overflow: hidden;
    }
</style>
