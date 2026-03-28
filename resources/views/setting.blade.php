<x-view.layout.app title="Pengaturan">

    <div id="view-settings" class="view-section hidden flex flex-col flex-1 h-full">
        <div class="flex-1 overflow-y-auto p-5 md:p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-2 mb-6 text-sm text-secondary">
                    <a href="{{ route('dashboard') }}" onclick="switchView('dashboard')"
                        class="hover:text-primary transition-colors">Dashboard</a>
                    <i data-lucide="chevron-right" class="size-4"></i>
                    <span class="font-medium text-foreground">Pengaturan Sistem</span>
                </div>

                <form id="formSetting" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <h1 class="font-bold text-3xl text-foreground">Pengaturan Sistem</h1>
                            <p class="text-sm text-secondary mt-1">Konfigurasi profil toko, metode pembayaran, dan
                                operasional</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all cursor-pointer">
                                <i data-lucide="save" class="size-4"></i>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">

                            <div class=" border border-border rounded-2xl shadow-sm p-6">
                                <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                                    <i data-lucide="store" class="size-5 text-primary"></i>
                                    Profil Toko
                                </h3>

                                <div class="flex flex-col sm:flex-row gap-6">
                                    <div class="flex flex-col items-center gap-3 shrink-0">

                                        <input type="file" id="logoInput" name="logo" class="hidden"
                                            accept="image/*">

                                        <div onclick="document.getElementById('logoInput').click()"
                                            class="size-28 bg-muted rounded-2xl flex items-center justify-center border-2 border-dashed border-secondary/30 cursor-pointer hover:border-primary hover:bg-primary/5 transition-all group overflow-hidden">

                                            <img id="logoPreview"
                                                src="{{ $setting?->logo ? asset('setting/logo/' . $setting->logo) : '' }}"
                                                class="object-cover w-full h-full {{ $setting?->logo ? '' : 'hidden' }}">

                                            <div id="logoPlaceholder"
                                                class="text-center {{ $setting?->logo ? 'hidden' : '' }}">
                                                <i data-lucide="upload-cloud"
                                                    class="size-6 text-secondary group-hover:text-primary mx-auto mb-1"></i>
                                                <span
                                                    class="text-xs text-secondary group-hover:text-primary font-medium">
                                                    Upload Logo
                                                </span>
                                            </div>

                                        </div>

                                        <p class="text-xs text-secondary text-center max-w-[120px]">
                                            PNG/JPG Max 2MB
                                        </p>

                                    </div>

                                    <div class="flex-1 space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="flex flex-col gap-2">
                                                <label class="text-sm font-semibold text-foreground">Nama Toko</label>
                                                <input type="text" name="nama_toko"
                                                    value="{{ $setting->nama_toko ?? '' }}"
                                                    class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <label class="text-sm font-semibold text-foreground">Email Resmi</label>
                                                <input type="email" name="email" value="{{ $setting->email ?? '' }}"
                                                    class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="text-sm font-semibold text-foreground">Nomor Telepon /
                                                WhatsApp</label>
                                            <input type="text" name="no_telepon"
                                                value="{{ $setting->no_telepon ?? '' }}"
                                                class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="text-sm font-semibold text-foreground">Alamat Lengkap</label>
                                            <textarea rows="3" name="alamat"
                                                class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">{{ $setting->alamat ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" border border-border rounded-2xl shadow-sm p-6">
                                <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                                    <i data-lucide="credit-card" class="size-5 text-primary"></i>
                                    Metode Pembayaran
                                </h3>

                                <div class="space-y-6 divide-y divide-border">
                                    <div class="pt-4 first:pt-0">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                                    <i data-lucide="building-2" class="size-5"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-sm">Transfer Bank Manual</p>
                                                    <p class="text-xs text-secondary">Terima transfer ke rekening
                                                        perusahaan
                                                    </p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="bank_active"
                                                    {{ $setting?->bank_active ? 'checked' : '' }} class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after: after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                        <div class="bg-muted/30 p-4 rounded-xl space-y-3">
                                            <div id="rekeningContainer" class="bg-muted/30 p-4 rounded-xl space-y-3">
                                                @if ($setting?->rekening_bank)
                                                    @foreach ($setting->rekening_bank as $bank)
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                            <input type="text" name="nama_bank[]"
                                                                value="{{ $bank['bank'] }}"
                                                                class="px-3 py-2 rounded-lg border border-border text-sm">

                                                            <input type="text" name="no_rekening[]"
                                                                value="{{ $bank['rekening'] }}"
                                                                class="px-3 py-2 rounded-lg border border-border text-sm">

                                                            <input type="text" name="atas_nama[]"
                                                                value="{{ $bank['nama'] }}"
                                                                class="px-3 py-2 rounded-lg border border-border text-sm">
                                                        </div>
                                                    @endforeach
                                                @endif
                                                <button type="button" id="tambahRekening"
                                                    class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                                    <i data-lucide="plus" class="size-3"></i>
                                                    Tambah Rekening
                                                </button>
                                            </div>
                                        </div>

                                        <div class="pt-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 bg-red-50 rounded-lg text-red-600">
                                                        <i data-lucide="qr-code" class="size-5"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-sm">QRIS Static</p>
                                                        <p class="text-xs text-secondary">Upload kode QRIS untuk discan
                                                            pelanggan</p>
                                                    </div>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="qris_active"
                                                        {{ $setting->qris_active ? 'checked' : '' }}
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after: after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="flex items-center gap-4 bg-muted/30 p-4 rounded-xl">
                                                <img src="{{ $setting?->qris ? asset('setting/qris/' . $setting->qris) : '' }}"
                                                    id="qrisPreview"
                                                    class="size-16 object-cover rounded-lg border border-border {{ $setting?->qris ? '' : 'hidden' }}">
                                                <div>
                                                    <p class="text-xs font-medium mb-2">QRIS_PrintHub.jpg</p>
                                                    <button type="button"
                                                        onclick="document.getElementById('qrisInput').click()"
                                                        class="px-3 py-1.5 border border-border rounded-lg text-xs font-semibold hover:bg-muted">
                                                        Upload / Ganti QRIS
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                                                        <i data-lucide="banknote" class="size-5"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-sm">Tunai / Cash</p>
                                                        <p class="text-xs text-secondary">Bayar di tempat saat ambil
                                                            barang
                                                        </p>
                                                    </div>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="cash_active"
                                                        {{ $setting->cash_active ? 'checked' : '' }}
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after: after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="lg:col-span-1 space-y-6">
                            <div class=" border border-border rounded-2xl shadow-sm p-6">
                                <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                                    <i data-lucide="clock" class="size-5 text-primary"></i>
                                    Jam Operasional
                                </h3>

                                <div class="space-y-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-foreground flex justify-between">
                                            <span>Senin - Jumat</span>
                                            <span
                                                class="text-xs font-normal text-success bg-success/10 px-2 py-0.5 rounded">Buka</span>
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input type="time" name="senin_buka"
                                                value="{{ $setting->jam_operasional['senin_jumat']['buka'] ?? '' }}"
                                                class="flex-1 px-3 py-2 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                            <span class="text-secondary">-</span>
                                            <input type="time" name="senin_tutup"
                                                value="{{ $setting->jam_operasional['senin_jumat']['tutup'] ?? '' }}"
                                                class="flex-1 px-3 py-2 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-foreground flex justify-between">
                                            <span>Sabtu</span>
                                            <span
                                                class="text-xs font-normal text-success bg-success/10 px-2 py-0.5 rounded">Buka</span>
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input type="time" name="sabtu_buka"
                                                value="{{ $setting->jam_operasional['sabtu']['buka'] ?? '' }}"
                                                class="flex-1 px-3 py-2 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                            <span class="text-secondary">-</span>
                                            <input type="time" name="sabtu_tutup"
                                                value="{{ $setting->jam_operasional['sabtu']['tutup'] ?? '' }}"
                                                class="flex-1 px-3 py-2 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="text-sm font-semibold text-foreground flex justify-between items-center">
                                            <span>Minggu / Libur</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="minggu_tutup"
                                                    {{ $setting->jam_operasional['minggu']['tutup'] ?? false ? 'checked' : '' }}
                                                    class="rounded text-primary focus:ring-primary border-gray-300">
                                                <span class="text-xs text-secondary">Tutup</span>
                                            </label>
                                        </label>
                                        <div class="flex items-center gap-2 opacity-50 pointer-events-none">
                                            <input type="time" value="00:00"
                                                class="flex-1 px-3 py-2 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm"
                                                disabled>
                                            <span class="text-secondary">-</span>
                                            <input type="time" value="00:00"
                                                class="flex-1 px-3 py-2 rounded-xl border border-border focus:ring-1 focus:ring-primary outline-none  text-sm"
                                                disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-border">
                                    <div class="flex items-start gap-3 bg-blue-50 p-3 rounded-xl">
                                        <i data-lucide="info" class="size-4 text-blue-600 mt-0.5 shrink-0"></i>
                                        <p class="text-xs text-blue-700">Jam operasional akan ditampilkan di
                                            halaman
                                            depan
                                            website dan formulir order pelanggan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            function switchView(view) {
                // Sembunyikan semua section
                document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));

                // Mapping view → id yang sebenarnya
                const viewMap = {
                    'list': 'view-settings',
                };

                const targetId = viewMap[view] || 'view-settings'; // fallback ke list
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

            document.addEventListener("DOMContentLoaded", function() {

                lucide.createIcons();

                /* =========================
                   PREVIEW LOGO TOKO
                ========================= */

                const logoInput = document.getElementById("logoInput");
                const logoPreview = document.getElementById("logoPreview");
                const logoPlaceholder = document.getElementById("logoPlaceholder");

                if (logoInput) {

                    logoInput.addEventListener("change", function() {

                        const file = this.files[0];

                        if (!file) return;

                        const reader = new FileReader();

                        reader.onload = function(e) {

                            logoPreview.src = e.target.result;
                            logoPreview.classList.remove("hidden");

                            if (logoPlaceholder) {
                                logoPlaceholder.classList.add("hidden");
                            }

                        };

                        reader.readAsDataURL(file);

                    });

                }


                /* =========================
                   PREVIEW QRIS
                ========================= */

                const qrisInput = document.getElementById("qrisInput");
                const qrisPreview = document.getElementById("qrisPreview");

                if (qrisInput) {

                    qrisInput.addEventListener("change", function() {

                        const file = this.files[0];

                        if (!file) return;

                        const reader = new FileReader();

                        reader.onload = function(e) {

                            qrisPreview.src = e.target.result;
                            qrisPreview.classList.remove("hidden");

                        };

                        reader.readAsDataURL(file);

                    });

                }


                /* =========================
                   TAMBAH REKENING BANK
                ========================= */

                const btnTambahRekening = document.getElementById("tambahRekening");
                const rekeningContainer = document.getElementById("rekeningContainer");

                if (btnTambahRekening) {

                    btnTambahRekening.addEventListener("click", function() {

                        const div = document.createElement("div");

                        div.className = "grid grid-cols-1 sm:grid-cols-3 gap-3";

                        div.innerHTML = `
                            <input type="text" name="nama_bank[]" 
                                placeholder="Nama Bank"
                                class="px-3 py-2 rounded-lg border border-border text-sm">

                            <input type="text" name="no_rekening[]" 
                                placeholder="No Rekening"
                                class="px-3 py-2 rounded-lg border border-border text-sm">

                            <input type="text" name="atas_nama[]" 
                                placeholder="Atas Nama"
                                class="px-3 py-2 rounded-lg border border-border text-sm">
                        `;

                        rekeningContainer.insertBefore(div, btnTambahRekening);

                    });

                }


                /* =========================
                   SUBMIT FORM SETTING
                ========================= */

                const form = document.getElementById("formSetting");

                if (form) {

                    form.addEventListener("submit", async function(e) {

                        e.preventDefault();

                        const button = form.querySelector("button[type='submit']");
                        const originalText = button.innerHTML;

                        button.innerHTML = "Menyimpan...";
                        button.disabled = true;

                        const formData = new FormData(form);

                        try {

                            const response = await fetch("/menu/setting-update", {
                                method: "POST",
                                body: formData,
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    "Accept": "application/json"
                                }
                            });

                            const data = await response.json();

                            if (data.success) {

                                alert("Pengaturan berhasil disimpan");

                                location.reload();

                            } else {

                                alert("Gagal menyimpan data");

                            }

                        } catch (error) {

                            console.error(error);
                            alert("Terjadi kesalahan pada server");

                        } finally {

                            button.innerHTML = originalText;
                            button.disabled = false;

                        }

                    });

                }

            });
        </script>
    @endpush

</x-view.layout.app>
