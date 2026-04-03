@if ($kartu->isEmpty())
    <tr>
        <td colspan="9" class="p-6 text-center text-sm text-secondary">
            Tidak ada data.
        </td>
    </tr>
@else
    @foreach ($kartu as $index => $item)
        <tr class="group hover:bg-muted/40 transition-all">

            {{-- NO --}}
            <td class="px-6 py-4 font-semibold text-primary">
                {{ $kartu->firstItem() + $index }}
            </td>

            {{-- PRODUK --}}
            <td class="p-4 pl-6">
                <div class="flex items-center gap-3">

                    <div
                        class="size-11 rounded-xl overflow-hidden flex items-center justify-center bg-primary/10 border border-border">
                        @if ($item->produk->foto_produk)
                            <img src="{{ asset('produk/' . $item->produk->foto_produk) }}"
                                class="w-full h-full object-cover">
                        @else
                            <i data-lucide="image" class="size-5 text-primary"></i>
                        @endif
                    </div>

                    <div>
                        <p class="font-semibold text-foreground group-hover:text-primary transition-colors">
                            {{ $item->produk->nama_produk }}
                        </p>

                        <p class="text-xs text-secondary">
                            Kode: {{ $item->produk->kode_produk }}
                        </p>
                    </div>

                </div>
            </td>

            {{-- TANGGAL --}}
            <td class="p-4 pl-6">
                <div>
                    <p class="font-medium text-foreground">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                    </p>
                    <p class="text-xs text-secondary">
                        {{ $item->kode_transaksi }}
                    </p>
                </div>
            </td>

            {{-- TIPE --}}
            <td class="p-4">

                @if ($item->tipe == 'masuk')
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">

                        <i data-lucide="arrow-down-circle" class="size-4"></i>
                        Stok Masuk

                    </span>
                @elseif ($item->tipe == 'keluar')
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">

                        <i data-lucide="arrow-up-circle" class="size-4"></i>
                        Stok Keluar

                    </span>
                @elseif ($item->tipe == 'koreksi_masuk')
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">

                        <i data-lucide="refresh-cw" class="size-4"></i>
                        Koreksi Masuk

                    </span>
                @elseif ($item->tipe == 'koreksi_keluar')
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">

                        <i data-lucide="refresh-cw" class="size-4"></i>
                        Koreksi Keluar

                    </span>
                @endif

            </td>

            {{-- QTY --}}
            <td class="px-6 py-4 font-bold text-lg">

                @if ($item->tipe == 'masuk' || $item->tipe == 'koreksi_masuk')
                    <span class="text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1">
                        <i data-lucide="plus" class="size-4"></i>
                        {{ $item->qty }}
                    </span>
                @else
                    <span class="text-rose-600 dark:text-rose-400 flex items-center justify-center gap-1">
                        <i data-lucide="minus" class="size-4"></i>
                        {{ $item->qty }}
                    </span>
                @endif

            </td>

            {{-- STOK SEBELUM --}}
            <td class="p-4 text-sm font-semibold text-secondary text-center">
                {{ $item->stok_sebelum }}
            </td>

            {{-- STOK SESUDAH --}}
            <td class="p-4 text-sm font-bold text-center">

                @if ($item->stok_sesudah > $item->stok_sebelum)
                    <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">

                        <i data-lucide="trending-up" class="size-4"></i>
                        {{ $item->stok_sesudah }}

                    </span>
                @elseif ($item->stok_sesudah < $item->stok_sebelum)
                    <span class="flex items-center gap-1 text-rose-600 dark:text-rose-400">

                        <i data-lucide="trending-down" class="size-4"></i>
                        {{ $item->stok_sesudah }}

                    </span>
                @else
                    <span class="text-secondary">
                        {{ $item->stok_sesudah }}
                    </span>
                @endif

            </td>

            {{-- KETERANGAN --}}
            <td class="p-4 text-sm text-secondary max-w-[220px] truncate">
                {{ $item->keterangan ?? '-' }}
            </td>

            {{-- ACTION --}}
            <td class="px-6 py-4 text-right">
                <button onclick="openDetailKartu({{ $item->id }})"
                    class="px-3 py-1.5 border border-border rounded-lg text-xs font-semibold hover:bg-primary hover:text-white hover:border-primary transition-colors cursor-pointer flex items-center gap-1">

                    <i data-lucide="eye" class="size-4"></i>
                    Detail
                </button>
            </td>
        </tr>
    @endforeach
@endif
