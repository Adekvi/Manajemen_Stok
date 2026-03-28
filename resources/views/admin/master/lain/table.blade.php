@if ($info->isEmpty())
    <tr>
        <td colspan="5" class="p-4 text-center text-sm text-secondary">
            Tidak ada pengumuman.
        </td>
    </tr>
@else
    @foreach ($info as $index => $item)
        <tr class="border-b border-border hover:bg-card-grey/50 transition-colors">
            <td class="p-4">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <i data-lucide="info" class="size-4"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-foreground">{{ $item->judul }}</p>
                        <p class="text-xs text-secondary">{{ $item->konten }}</p>
                    </div>
                </div>
            </td>
            <td class="p-4 text-sm text-secondary">
                {{ \Carbon\Carbon::parse($item->tgl)->translatedFormat('d M Y') }}</td>
            <td class="p-4 text-sm text-secondary">Semua User</td>
            <td class="p-4">
                @if ($item->status == 'aktif')
                    <span class="bg-success/10 text-success px-3 py-1 rounded-full text-xs font-semibold">
                        Aktif
                    </span>
                @else
                    <span class="bg-danger/10 text-danger px-3 py-1 rounded-full text-xs font-semibold">
                        Nonaktif
                    </span>
                @endif
            </td>
            <td class="p-4 text-right">
                <button
                    onclick="openEditModal(
                            '{{ $item->id }}',
                            '{{ $item->judul }}',
                            '{{ $item->konten }}',
                            '{{ $item->tgl }}',
                            '{{ $item->status }}'
                        )"
                    class="p-2 text-secondary hover:text-primary transition-colors cursor-pointer">
                    <i data-lucide="edit-3" class="size-4"></i>
                </button>

                <button onclick="deleteAnnouncement('{{ $item->id }}')"
                    class="p-2 text-secondary hover:text-error transition-colors cursor-pointer">
                    <i data-lucide="trash-2" class="size-4"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
