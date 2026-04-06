@if ($menus->isEmpty())
    <tr>
        <td colspan="6" class="p-4 text-center text-sm text-secondary">Tidak ada menu</td>
    </tr>
@else
    @foreach ($menus as $index => $item)
        <tr class="hover:bg-muted/30 transition-colors">

            <!-- NO -->
            <td class="p-4 pl-6">{{ $menus->firstItem() + $index }}</td>

            <!-- NAMA MENU -->
            <td class="p-4 pl-6">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-lg flex items-center justify-center bg-primary/10">
                        <i data-lucide="{{ $item->icon ?? 'menu' }}" class="size-5 text-primary"></i>
                    </div>
                    <div>
                        <p class="font-medium text-foreground">{{ $item->name }}</p>
                        <p class="text-xs text-secondary">Group: {{ $item->group }}</p>
                    </div>
                </div>
            </td>

            <!-- ROUTE / URL -->
            <td class="p-4 font-medium text-sm text-secondary">
                {{ $item->route ?? '-' }}
            </td>

            <!-- ICON -->
            <td class="p-4">
                <span class="text-xs bg-muted px-2 py-1 rounded-lg">
                    {{ $item->icon }}
                </span>
            </td>

            <!-- STATUS -->
            <td class="p-4">
                @if ($item->is_active ?? true)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                        Aktif
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        Nonaktif
                    </span>
                @endif
            </td>

            <!-- AKSI -->
            {{-- <td class="p-4 pr-6 text-right">
                <div class="flex items-center justify-end gap-2">

                    <button onclick="editData({{ $item->id }})"
                        class="p-2 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg">
                        <i data-lucide="pencil" class="size-4"></i>
                    </button>

                    <button onclick="deleteData({{ $item->id }})"
                        class="p-2 text-secondary hover:text-error hover:bg-error/5 rounded-lg">
                        <i data-lucide="trash-2" class="size-4"></i>
                    </button>

                </div>
            </td> --}}

        </tr>
    @endforeach
@endif
