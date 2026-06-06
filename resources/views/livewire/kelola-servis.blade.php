<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Riwayat & Kelola Servis Aset</h2>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Registrasi Servis -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 lg:col-span-1 h-fit">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b border-gray-100 pb-3">Catat Servis / Perbaikan</h3>
            <form wire:submit.prevent="simpan">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Aset Fisik (SN)</label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <select wire:model="id_inventaris" id="id_inventaris_select" required class="flex-1 min-w-0 w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">-- Pilih Aset --</option>
                                @foreach($asets as $a)
                                    <option value="{{ $a->id_inventaris }}">{{ $a->serial_number }} - {{ $a->barang->nama_barang ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openScanner()" class="px-4 py-2.5 bg-gray-800 text-white font-semibold rounded-md hover:bg-gray-900 transition-colors flex justify-center items-center shadow-sm w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Scan
                            </button>
                        </div>
                        @error('id_inventaris') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Servis</label>
                        <input type="date" wire:model="tgl_servis" required class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        @error('tgl_servis') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rincian Kerusakan</label>
                        <textarea wire:model="rincian_kerusakan" required rows="3" placeholder="Contoh: Baterai soak, layar pecah..." class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"></textarea>
                        @error('rincian_kerusakan') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Biaya Servis (Rp)</label>
                        <input type="number" wire:model="biaya_servis" min="0" placeholder="0" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        @error('biaya_servis') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Aset Setelah Servis</label>
                        <select wire:model="status_kondisi_baru" required class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="Servis">Masih Diservis (Proses)</option>
                            <option value="Tersedia">Sudah Selesai (Kembali Tersedia)</option>
                            <option value="Rusak">Gagal Servis (Rusak Permanen)</option>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">*Pilihan ini akan otomatis memperbarui kondisi aset di sistem.</p>
                        @error('status_kondisi_baru') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Simpan Data Servis
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat Servis -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 lg:col-span-2 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Riwayat Perbaikan</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Aset & SN</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Rincian Kerusakan</th>
                            <th class="px-6 py-4">Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayats as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $r->inventarisFisik->barang->nama_barang ?? 'N/A' }}</p>
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-mono border border-gray-200">{{ $r->inventarisFisik->serial_number ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-600">{{ date('d M Y', strtotime($r->tgl_servis)) }}</td>
                            <td class="px-6 py-4 text-gray-600 text-xs">{{ $r->rincian_kerusakan }}</td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">Rp {{ number_format($r->biaya_servis, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data riwayat servis dicatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scanner Modal Overlay -->
    <div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden shadow-2xl transform transition-transform scale-95" id="scannerModalContent">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Scan KTP Aset (SN)</h3>
                <button type="button" onclick="closeScanner()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 bg-black">
                <div id="reader" width="100%"></div>
            </div>
            <div class="p-4 bg-gray-50 text-center">
                <p class="text-sm text-gray-500">Arahkan kamera ke Barcode/QR Code pada fisik aset.</p>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
<script>
    let html5QrcodeScanner = null;

    function openScanner() {
        document.getElementById('scannerModal').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('scannerModalContent').classList.remove('scale-95');
            document.getElementById('scannerModalContent').classList.add('scale-100');
        }, 50);

        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 },
            /* verbose= */ false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function closeScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                document.getElementById('scannerModalContent').classList.remove('scale-100');
                document.getElementById('scannerModalContent').classList.add('scale-95');
                setTimeout(() => {
                    document.getElementById('scannerModal').classList.add('hidden');
                }, 150);
            }).catch(error => console.error("Failed to clear scanner", error));
        } else {
            document.getElementById('scannerModal').classList.add('hidden');
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        closeScanner();
        
        // Cari value (id_inventaris) berdasarkan text SN (decodedText)
        let selectEl = document.getElementById('id_inventaris_select');
        let options = selectEl.options;
        let foundValue = null;
        
        for(let i=0; i<options.length; i++) {
            // Karena format option text adalah "SN-123 - Nama Barang"
            if (options[i].text.startsWith(decodedText + ' -') || options[i].text === decodedText) {
                foundValue = options[i].value;
                break;
            }
        }

        if (foundValue) {
            @this.set('id_inventaris', foundValue);
            // Opsional alert kecil
            // alert('Aset ' + decodedText + ' berhasil ditemukan dan dipilih.');
        } else {
            alert('Aset dengan Serial Number: ' + decodedText + ' tidak ditemukan di database.');
        }
    }

    function onScanFailure(error) {
        // Abaikan
    }
</script>
