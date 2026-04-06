@if ($produk->isEmpty())
    <tr>
        <td colspan="6" class="p-4 text-center text-sm text-secondary">Tidak ada produk</td>
    </tr>
@else
    @foreach ($produk as $index => $item)
        <tr class="hover:bg-muted/30 transition-colors">
            <td class="p-4 pl-6">{{ $produk->firstItem() + $index }}</td>
            <td class="p-4 pl-6">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-lg overflow-hidden flex items-center justify-center bg-primary/10">
                        @if ($item->foto_produk)
                            <img src="{{ asset('produk/' . $item->foto_produk) }}" alt="{{ $item->nama_produk }}"
                                class="w-full h-full object-cover">
                        @else
                            <i data-lucide="image" class="size-5 text-primary"></i>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-foreground">{{ $item->nama_produk }}</p>
                        <p class="text-xs text-secondary">Kode: {{ $item->kode_produk }}</p>
                    </div>
                </div>
            </td>
            <td class="p-4 font-medium">
                @if ($item->stok > 10)
                    <!-- HIJAU -->
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                        <span class="font-medium">
                            {{ $item->stok }} -
                        </span>

                        <i data-lucide="badge-check" class="size-4"></i>
                        Stok Aman

                    </span>
                @elseif ($item->stok > 0 && $item->stok <= 10)
                    <!-- KUNING -->
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400">
                        <span class="font-medium">
                            {{ $item->stok }} -
                        </span>

                        <i data-lucide="alert-triangle" class="size-4"></i>
                        Stok Menipis

                    </span>
                @elseif ($item->stok === 0)
                    <!-- KUNING -->
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400">
                        <span class="font-medium">
                            {{ $item->stok }} -
                        </span>

                        <i data-lucide="alert-triangle" class="size-4"></i>
                        Stok Habis

                    </span>
                @elseif ($item->stok < 0)
                    <!-- MERAH -->
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400">
                        <span class="font-medium">
                            {{ $item->stok }} -
                        </span>

                        <i data-lucide="circle-x" class="size-4"></i>
                        Stok Minus
                    </span>
                @endif
            </td>

            <td class="p-4 font-medium">Rp. {{ number_format($item->harga, 0, ',', '.') }}
                <span class="text-xs text-secondary font-normal">/
                    {{ $item->satuan }}</span>
            </td>
            <td class="p-4">
                @if ($item->status == 'aktif')
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                        Aktif
                    </span>
                @elseif ($item->status == 'nonaktif')
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-error/10 text-error">
                        Rejected
                    </span>
                @endif
            </td>
            <td class="p-4 pr-6 text-right">
                <div class="flex items-center justify-end gap-2">
                    <!-- DETAIL -->
                    <button onclick="showDetail({{ $item->id }})"
                        class="p-2 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
                        title="Detail">
                        <i data-lucide="eye" class="size-4"></i>
                    </button>
                    <!-- EDIT -->
                    <button onclick="editData({{ $item->id }})"
                        class="p-2 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
                        title="Edit">
                        <i data-lucide="pencil" class="size-4"></i>
                    </button>
                    <!-- DELETE -->
                    <button onclick="deleteData({{ $item->id }})"
                        class="p-2 text-secondary hover:text-error hover:bg-error/5 rounded-lg transition-colors"
                        title="Hapus">
                        <i data-lucide="trash-2" class="size-4"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
@endif
