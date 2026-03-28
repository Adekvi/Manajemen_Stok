@if ($user->isEmpty())
    <tr>
        <td colspan="5" class="p-4 text-center text-sm text-secondary">
            Tidak ada user.
        </td>
    </tr>
@else
    @foreach ($user as $item)
        <tr data-role="owner" data-searchable="Admin Kos admin@kosmanager.com"
            class="border-b border-border hover:bg-card-grey/50 transition-colors">
            <td class="p-4">
                <div class="flex items-center gap-3">
                    @if ($item->dataDiri->foto_diri)
                        <img src="{{ asset('foto_profile/' . $item->dataDiri->foto_diri) }}"
                            alt="{{ strtoupper(substr($item->dataDiri->username, 0, 2)) }}"
                            class="size-9 rounded-full object-cover">
                    @else
                        <i data-lucide="image" class="size-5 text-primary"></i>
                    @endif
                    <span class="font-semibold text-foreground">{{ $item->username }}</span>
                </div>
            </td>
            <td class="p-4 text-sm text-secondary">{{ $item->email }}</td>
            <td class="p-4"><span
                    class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase">{{ $item->role }}</span>
            </td>
            <td class="p-4">
                <span class="bg-success/10 text-success px-3 py-1 rounded-full text-xs font-semibold">
                    Aktif
                </span>
            </td>
            <td class="p-4 text-right">
                <button onclick="showToast('Edit User')"
                    class="p-2 text-secondary hover:text-primary transition-colors cursor-pointer"><i
                        data-lucide="edit-3" class="size-4"></i></button>
            </td>
        </tr>
    @endforeach
@endif
