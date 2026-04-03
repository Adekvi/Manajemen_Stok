@forelse ($recentTransaksi as $kel)
    <tr class="hover:bg-muted/30 transition">
        <!-- WAKTU -->
        <td class="p-4 pl-6 text-foreground">
            <div class="flex flex-col leading-tight">
                <span class="font-medium">
                    {{ $kel->created_at->format('d M Y') }}
                </span>
                <span class="text-xs text-secondary">
                    {{ $kel->created_at->format('H:i') }}
                </span>
            </div>
        </td>
        <!-- USER -->
        <td class="p-4">
            <div class="flex items-center gap-3">
                <div
                    class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">
                    @if ($kel->creator && $kel->creator->dataDiri && $kel->creator->dataDiri->foto_diri)
                        <img src="{{ asset('foto_profile/' . $kel->creator->dataDiri->foto_diri) }}"
                            class="size-9 rounded-full object-cover">
                    @else
                        {{ $kel->creator ? strtoupper(substr($kel->creator->username, 0, 2)) : 'AD' }}
                    @endif
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-medium text-foreground">
                        {{ $kel->creator->username }}
                    </span>
                    <span class="text-xs text-secondary">
                        User
                    </span>
                </div>
            </div>
        </td>
        <!-- AKTIVITAS -->
        <td class="p-4">
            <span class="text-secondary">Mengeluarkan</span>
            <span class="font-semibold text-foreground">
                {{ $kel->jumlah }}
            </span>
            <span class="text-secondary">produk</span>
            <span class="font-medium text-primary">
                {{ $kel->produk->nama_produk }}
            </span>
        </td>
        <!-- STATUS -->
        <td class="p-4">
            @if ($kel->status == 'posted')
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                    <i data-lucide="check-circle" class="size-4"></i>
                    Posted
                </span>
            @elseif ($kel->status == 'cancelled')
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-600">
                    <i data-lucide="x-circle" class="size-4"></i>
                    Cancelled
                </span>
            @else
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                    <i data-lucide="clock" class="size-4"></i>
                    Draft
                </span>
            @endif
        </td>
    </tr>
@empty
    <!-- EMPTY STATE -->
    <tr>
        <td colspan="4" class="p-10 text-center">
            <div class="flex flex-col items-center justify-center gap-3 text-secondary">
                <div class="size-14 rounded-full bg-muted flex items-center justify-center">
                    <i data-lucide="inbox" class="size-6"></i>
                </div>
                <div class="text-sm">
                    <p class="font-medium text-foreground">Belum ada data</p>
                    <p class="text-xs">Transaksi stok keluar akan muncul di sini</p>
                </div>
            </div>
        </td>
    </tr>
@endforelse
