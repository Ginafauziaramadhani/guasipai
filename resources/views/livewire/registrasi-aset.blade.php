<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Registrasi Aset Fisik & SN</h2>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Registrasi -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 lg:col-span-1 h-fit">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b border-gray-100 pb-3">Daftarkan Aset Baru</h3>
            <form wire:submit.prevent="simpan">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Master Aset</label>
                        <select wire:model="id_barang" required class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Pilih Barang (Aset) --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}">{{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                        @error('id_barang') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-gray-400 mt-1">*Hanya barang dengan kategori "Aset" yang muncul di sini.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Serial Number (SN)</label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" wire:model="serial_number" id="serial_number_input" required placeholder="Cth: HT-MOT-001" class="flex-1 min-w-0 w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors uppercase">
                            <button type="button" onclick="openScanner()" class="px-4 py-2.5 bg-gray-800 text-white font-semibold rounded-md hover:bg-gray-900 transition-colors flex justify-center items-center shadow-sm w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Scan
                            </button>
                        </div>
                        @error('serial_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi/Unit Saat Ini (Opsional)</label>
                        <select wire:model="id_unit_sekarang" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Gudang Pusat (Default) --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id_unit }}">{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                        @error('id_unit_sekarang') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kondisi Awal</label>
                        <select wire:model="status_kondisi" required class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="Tersedia">Tersedia (Bagus)</option>
                            <option value="Terdistribusi">Terdistribusi (Sudah Keluar)</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Servis">Dalam Perbaikan (Servis)</option>
                        </select>
                        @error('status_kondisi') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Registrasi Aset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 lg:col-span-2 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-700">Database Inventaris Fisik (SN)</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Barang</th>
                            <th class="px-6 py-4">Serial Number</th>
                            <th class="px-6 py-4">Lokasi Saat Ini</th>
                            <th class="px-6 py-4">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($asets as $a)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $a->barang->nama_barang ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-gray-600 tracking-wider">
                                <span class="bg-gray-100 px-2 py-1 rounded border border-gray-200">{{ $a->serial_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $a->unitKerja->nama_unit ?? 'Gudang Pusat' }}</td>
                            <td class="px-6 py-4">
                                @if($a->status_kondisi == 'Tersedia')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Tersedia</span>
                                @elseif($a->status_kondisi == 'Terdistribusi')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">Terdistribusi</span>
                                @elseif($a->status_kondisi == 'Rusak')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Rusak</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">Servis</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada aset fisik yang diregistrasi.</td>
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
                <h3 class="text-lg font-bold text-gray-800">Scan Barcode / QR</h3>
                <button type="button" onclick="closeScanner()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 bg-black">
                <!-- Tempat kamera dirender -->
                <div id="reader" width="100%"></div>
            </div>
            <div class="p-4 bg-gray-50 text-center">
                <p class="text-sm text-gray-500">Arahkan kamera ke Barcode atau QR Code aset.</p>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
<script>
    let html5QrcodeScanner = null;

    function openScanner() {
        document.getElementById('scannerModal').classList.remove('hidden');
        // Sedikit animasi
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
                // Sembunyikan modal
                document.getElementById('scannerModalContent').classList.remove('scale-100');
                document.getElementById('scannerModalContent').classList.add('scale-95');
                setTimeout(() => {
                    document.getElementById('scannerModal').classList.add('hidden');
                }, 150);
            }).catch(error => {
                console.error("Failed to clear html5QrcodeScanner. ", error);
            });
        } else {
            document.getElementById('scannerModal').classList.add('hidden');
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scanner
        closeScanner();
        
        // Memasukkan hasil scan ke dalam properti Livewire 'serial_number'
        @this.set('serial_number', decodedText);
        
        // (Opsional) memainkan suara beep jika diperlukan
    }

    function onScanFailure(error) {
        // handle scan failure, biasanya diabaikan agar terus mencoba scan
        // console.warn(`Code scan error = ${error}`);
    }
</script>
