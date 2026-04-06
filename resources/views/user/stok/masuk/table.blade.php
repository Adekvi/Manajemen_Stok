@if ($masuk->isEmpty())
    <tr>
        <td colspan="7" class="p-4 text-center text-sm text-secondary">Tidak ada data
            stok masuk</td>
    </tr>
@else
    @foreach ($masuk as $index => $item)
        <tr class="hover:bg-muted/30 transition-colors">
            <td class="p-4 pl-6">{{ $masuk->firstItem() + $index }}</td>
            <td class="p-4 pl-6">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="font-medium text-foreground">
                            {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d M Y') }}
                        </p>
                        <p class="text-xs text-secondary">Kode: {{ $item->kode_transaksi }}</p>
                    </div>
                </div>
            </td>
            <td class="p-4 pl-6">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-lg overflow-hidden flex items-center justify-center bg-primary/10">
                        @if ($item->produk->foto_produk)
                            <img src="{{ asset('produk/' . $item->produk->foto_produk) }}"
                                alt="{{ $item->produk->nama_produk }}" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="image" class="size-5 text-primary"></i>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-foreground">{{ $item->produk->nama_produk }}</p>
                        <p class="text-xs text-secondary">Kode: {{ $item->produk->kode_produk }}</p>
                    </div>
                </div>
            </td>
            <td class="p-4 text-sm font-medium text-center">
                <span class="text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1">
                    <i data-lucide="plus" class="size-4"></i>
                    {{ $item->jumlah }}
                </span>
            </td>
            <td class="p-4 font-medium">Rp. {{ number_format($item->produk->harga, 0, ',', '.') }}
                <span class="text-xs text-secondary font-normal">/
                    {{ $item->produk->satuan }}</span>
            </td>
            <td class="p-4">
                <button
                    onclick='openStatusModal(
                    {{ $item->id }},
                    "{{ $item->status }}",
                    {
                        nama_produk: "{{ $item->produk->nama_produk }}",
                        kode_produk: "{{ $item->produk->kode_produk }}",
                        foto_produk: "{{ $item->produk->foto_produk }}"
                    }
                    )'>
                    @if ($item->status == 'draft')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                            Draft
                        </span>
                    @elseif ($item->status == 'posted')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            Posting
                        </span>
                    @elseif ($item->status == 'cancelled')
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-error/10 text-error">
                            Cancel
                        </span>
                    @endif
                </button>
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
                    {{-- <button onclick="deleteData({{ $item->id }})"
                        class="p-2 text-secondary hover:text-error hover:bg-error/5 rounded-lg transition-colors"
                        title="Hapus">
                        <i data-lucide="trash-2" class="size-4"></i>
                    </button> --}}
                </div>
            </td>
        </tr>
    @endforeach
@endif
