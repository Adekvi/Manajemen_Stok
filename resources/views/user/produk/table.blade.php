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
                {{ $item->stok }}
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
                </div>
            </td>
        </tr>
    @endforeach
@endif
