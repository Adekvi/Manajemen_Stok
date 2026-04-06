@if ($users->isEmpty())
    <tr>
        <td colspan="6" class="p-4 text-center text-sm text-secondary">
            Tidak ada users.
        </td>
    </tr>
@else
    @foreach ($users as $item)
        <tr class="border-b border-border hover:bg-card-grey/50 transition-colors">
            <td class="p-4">
                <div class="flex items-center gap-3">

                    {{-- Foto Profil dengan penanganan null yang aman --}}
                    <div class="flex-shrink-0">
                        @if ($item->dataDiri && $item->dataDiri->foto_diri)
                            <img src="{{ asset('foto_profile/' . $item->dataDiri->foto_diri) }}"
                                alt="{{ $item->username }}" class="size-9 rounded-full object-cover border border-border">
                        @else
                            {{-- Avatar Default (Initials) - Lebih bagus daripada icon image --}}
                            <div
                                class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold text-sm border border-primary/20">
                                {{ strtoupper(substr($item->username, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <span class="font-semibold text-foreground block">{{ $item->username }}</span>
                        @if ($item->dataDiri && $item->dataDiri->nama_lengkap)
                            <span class="text-xs text-secondary">{{ $item->dataDiri->nama_lengkap }}</span>
                        @endif
                    </div>
                </div>
            </td>

            <td class="p-4 text-sm text-secondary">{{ $item->email ?? '-' }}</td>

            {{-- <td class="p-4">
                @foreach ($item->roles as $role)
                    <span
                        class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase mr-1 mb-1">
                        {{ ucfirst($role->name) }}
                    </span>
                @endforeach

                @if ($item->roles->isEmpty())
                    <span class="text-secondary text-xs">No Role</span>
                @endif
            </td> --}}
            <td class="p-4">{!! $item->role_badge !!}</td>

            <td class="p-4">
                <button
                    onclick="openStatusModal({{ $item->id }}, {{ $item->is_active }}, '{{ addslashes($item->username) }}')"
                    class="cursor-pointer transition-all hover:scale-105">
                    @if ($item->is_active == 1 || $item->is_active === '1')
                        <span
                            class="bg-success/10 text-success px-4 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                            <span class="size-2 bg-success rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    @else
                        <span
                            class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                            <span class="size-2 bg-red-500 rounded-full"></span>
                            Nonaktif
                        </span>
                    @endif
                </button>
            </td>

            <td class="p-4">
                @if ($item->is_active && $item->is_online)
                    <!-- Aktif + Online -->
                    <span
                        class="bg-success/10 text-success px-4 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                        <span class="size-2 bg-success rounded-full animate-pulse"></span>
                        Online
                    </span>
                @else
                    <!-- Semua kondisi lain dianggap Offline -->
                    <span
                        class="bg-gray-100 text-gray-600 px-4 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                        <span class="size-2 bg-gray-400 rounded-full"></span>
                        Offline
                    </span>
                @endif
            </td>

            <td class="p-4 text-right">
                <button onclick="editData({{ $item->id }})"
                    class="p-2 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
                    title="Edit">
                    <i data-lucide="edit-3" class="size-4"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
