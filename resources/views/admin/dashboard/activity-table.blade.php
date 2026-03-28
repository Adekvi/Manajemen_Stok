@forelse ($recentTransaksi as $kel)
    <tr class="hover:bg-muted/30 transition">

        <!-- WAKTU -->
        <td class="p-4 pl-6 text-foreground">
            <div class="flex flex-col leading-tight">
                <span class="font-medium">
                    {{ $kel->created_at->format('d M Y') }}
                </span>
                <span class="text-xs text-secondary">
                    {{ $kel->created_at->diffForHumans() }}
                </span>
            </div>
        </td>

        <!-- USER -->
        <td class="p-4">
            <div class="flex items-center gap-3">
                <div
                    class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold shadow-sm">
                    @if ($kel->user && $kel->user->dataDiri && $kel->user->dataDiri->foto_diri)
                        <img src="{{ asset('foto_profile/' . $kel->user->dataDiri->foto_diri) }}"
                            class="size-9 rounded-full object-cover">
                    @else
                        {{ $kel->user ? strtoupper(substr($kel->user->username, 0, 2)) : 'AD' }}
                    @endif
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-medium text-foreground">
                        {{ $kel->user->username ?? 'Admin' }}
                    </span>
                    <span class="text-xs text-secondary">
                        {{ $kel->user ? 'User' : 'System Admin' }}
                    </span>
                </div>
            </div>
        </td>

        <!-- AKTIVITAS -->
        <td class="p-4">
            @php
                $config = match ($kel->tipe) {
                    'masuk' => [
                        'label' => 'Menambahkan',
                        'color' => 'text-green-600',
                        'icon' => 'arrow-down',
                    ],
                    'keluar' => [
                        'label' => 'Mengeluarkan',
                        'color' => 'text-red-600',
                        'icon' => 'arrow-up',
                    ],
                    'koreksi_masuk' => [
                        'label' => 'Koreksi Masuk',
                        'color' => 'text-yellow-600',
                        'icon' => 'rotate-ccw',
                    ],
                    'koreksi_keluar' => [
                        'label' => 'Koreksi Keluar',
                        'color' => 'text-orange-600',
                        'icon' => 'rotate-cw',
                    ],
                    default => [
                        'label' => 'Aktivitas',
                        'color' => 'text-gray-600',
                        'icon' => 'circle',
                    ],
                };
            @endphp

            <div class="flex items-center gap-2 group relative">

                <!-- ICON -->
                <div class="p-2 rounded-lg bg-muted/50 {{ $config['color'] }}">
                    <i data-lucide="{{ $config['icon'] }}" class="size-4"></i>
                </div>

                <!-- TEXT -->
                <div class="flex flex-col leading-tight">
                    <span class="text-sm">
                        <span class="text-secondary">{{ $config['label'] }}</span>
                        <span class="font-semibold text-foreground">
                            {{ $kel->qty }}
                        </span>
                        <span class="text-secondary">produk</span>
                    </span>

                    <span class="text-xs text-primary font-medium">
                        {{ $kel->produk->nama_produk ?? '-' }} -
                        <span class="text-secondary">
                            {{ $kel->produk->kode_produk ?? '-' }}
                        </span>
                    </span>
                </div>

                <!-- TOOLTIP -->
                @if ($kel->keterangan)
                    <div
                        class="absolute bottom-full mb-2 hidden group-hover:block bg-black text-white text-xs px-3 py-2 rounded-lg shadow-lg whitespace-nowrap z-50">
                        {{ $kel->keterangan }}
                    </div>
                @endif

            </div>
        </td>

        <!-- STATUS -->
        <td class="p-4">
            @if (str_contains($kel->tipe, 'masuk'))
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                    <i data-lucide="arrow-down" class="size-4"></i>
                    Masuk
                </span>
            @elseif (str_contains($kel->tipe, 'keluar'))
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                    <i data-lucide="arrow-up" class="size-4"></i>
                    Keluar
                </span>
            @else
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-600">
                    <i data-lucide="refresh-ccw" class="size-4"></i>
                    Koreksi
                </span>
            @endif
        </td>

    </tr>
@empty
    <tr>
        <td colspan="4" class="p-10 text-center">
            <div class="flex flex-col items-center gap-3 text-secondary">
                <div class="size-14 rounded-full bg-muted flex items-center justify-center shadow-inner">
                    <i data-lucide="inbox" class="size-6"></i>
                </div>
                <div class="text-sm">
                    <p class="font-medium text-foreground">Belum ada aktivitas</p>
                    <p class="text-xs">Semua transaksi akan muncul di sini</p>
                </div>
            </div>
        </td>
    </tr>
@endforelse
