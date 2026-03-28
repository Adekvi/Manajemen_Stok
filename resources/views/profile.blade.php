<x-view.layout.app title="My Profile">

    <div id="view-profile" class="page-section">
        <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
            <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                class="hover:text-primary transition-colors">Dashboard</a>
            <i data-lucide="chevron-right" class="size-4"></i>
            <span class="font-medium text-foreground">My Profile</span>
        </div>

        <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="rounded-2xl border border-border p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4 text-foreground">Identitas</h3>
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex flex-col items-center gap-3">

                        <!-- INPUT FILE (HIDDEN) -->
                        <input type="file" name="foto_diri" id="fotoInput" class="hidden"
                            accept="image/png, image/jpeg">

                        <!-- PREVIEW -->
                        <div id="uploadBox"
                            class="size-32 rounded-2xl bg-muted border-2 border-dashed border-secondary/30 flex flex-col items-center justify-center text-secondary hover:bg-muted/70 cursor-pointer transition-colors relative overflow-hidden group">

                            <!-- IMAGE -->
                            <img id="previewImage"
                                src="{{ $dataDiri && $dataDiri->foto_diri ? asset('foto_profile/' . $dataDiri->foto_diri) : '' }}"
                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ease-in-out {{ $dataDiri && $dataDiri->foto_diri ? 'opacity-100' : 'opacity-0' }}">

                            <!-- OVERLAY -->
                            <div id="uploadOverlay"
                                class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black/40 text-white transition-opacity duration-300 {{ $dataDiri && $dataDiri->foto_diri ? 'opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto' : 'opacity-100' }}">

                                <i data-lucide="upload-cloud" class="size-8 mb-1"></i>
                                <span class="text-xs font-semibold">Upload Foto</span>
                            </div>
                        </div>

                        <p class="text-xs text-secondary text-center">
                            Format: PNG, JPG<br>Max: 2MB
                        </p>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-secondary mb-2">Nama</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                value="{{ $dataDiri->nama_lengkap ?? '-' }}"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-secondary mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary/20 outline-none h-24 resize-none transition-all">{{ $dataDiri->alamat ?? '-' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_wa" id="no_wa"
                                value="{{ $dataDiri->no_wa ?? '0812-3456-7890' }}"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <option value="">-- Jenis Kelamin --</option>
                                <option value="L" {{ ($dataDiri->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="P" {{ ($dataDiri->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pb-8 mt-4">
                <button type="submit"
                    class="bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:scale-105 active:scale-95 cursor-pointer flex items-center gap-2">
                    <i data-lucide="save" class="size-5"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    @push('css')
        <style>
            #previewImage {
                transition: opacity 0.25s ease-in-out;
            }

            #uploadOverlay {
                transition: opacity 0.25s ease-in-out;
                background: rgba(0, 0, 0, 0.35);
                /* semi transparan gelap biar icon terlihat jelas */
            }
        </style>
    @endpush

    @push('js')
        <script>
            function switchView(view) {
                // Sembunyikan semua section
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                // Mapping view → id yang sebenarnya
                const viewMap = {
                    'list': 'view-profile',
                };

                const targetId = viewMap[view] || 'view-profile'; // fallback ke list
                const target = document.getElementById(targetId);

                if (target) {
                    target.classList.remove('hidden');
                }
            }

            // Default tampilan saat halaman dibuka
            document.addEventListener('DOMContentLoaded', () => {
                switchView('dashboard'); // ← pakai 'list' bukan 'stok-list'
                lucide.createIcons();
            });

            document.addEventListener('DOMContentLoaded', function() {
                const uploadBox = document.getElementById('uploadBox');
                const inputFile = document.getElementById('fotoInput');
                const preview = document.getElementById('previewImage');
                const overlay = document.getElementById('uploadOverlay');

                let currentImageURL = null;

                if (!uploadBox || !inputFile || !preview || !overlay) return;

                // Klik box → buka file picker
                uploadBox.addEventListener('click', function() {
                    inputFile.click();
                });

                inputFile.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;

                    const allowedTypes = ['image/jpeg', 'image/png'];
                    const maxSize = 2 * 1024 * 1024; // 2MB

                    // Validasi
                    if (!allowedTypes.includes(file.type)) {
                        alert('Format harus JPG atau PNG saja');
                        this.value = '';
                        return;
                    }

                    if (file.size > maxSize) {
                        alert('Ukuran maksimal 2MB');
                        this.value = '';
                        return;
                    }

                    // Bersihkan URL sebelumnya (penting untuk hindari memory leak)
                    if (currentImageURL) {
                        URL.revokeObjectURL(currentImageURL);
                        currentImageURL = null;
                    }

                    // Buat object URL baru
                    currentImageURL = URL.createObjectURL(file);

                    // Langkah anti-tumpang tindih:
                    // 1. Matikan dulu gambar lama sepenuhnya
                    preview.style.opacity = '0';
                    preview.src = ''; // kosongkan src dulu
                    overlay.style.opacity = '1'; // tampilkan overlay sementara

                    // 2. Beri jeda kecil agar browser benar-benar hapus render lama
                    setTimeout(() => {
                        preview.src = currentImageURL;

                        // 3. Tunggu gambar benar-benar load
                        const loadHandler = () => {
                            preview.style.opacity = '1';
                            overlay.style.opacity = '0';
                            preview.removeEventListener('load', loadHandler);
                        };

                        preview.addEventListener('load', loadHandler);

                        // Fallback jika gambar error / corrupt
                        preview.onerror = () => {
                            alert('Gagal memuat gambar');
                            inputFile.value = '';
                            preview.src = '';
                            preview.style.opacity = '0';
                            overlay.style.opacity = '1';
                        };
                    }, 30); // 30ms biasanya cukup, bisa dinaikkan ke 50–80 jika masih ada bug
                });

                // Optional: support drag & drop (bonus UX)
                uploadBox.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadBox.classList.add('bg-muted/90', 'border-primary/50');
                });

                uploadBox.addEventListener('dragleave', () => {
                    uploadBox.classList.remove('bg-muted/90', 'border-primary/50');
                });

                uploadBox.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadBox.classList.remove('bg-muted/90', 'border-primary/50');
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        inputFile.files = e.dataTransfer.files;
                        inputFile.dispatchEvent(new Event('change'));
                    }
                });
            });
        </script>
    @endpush
</x-view.layout.app>
