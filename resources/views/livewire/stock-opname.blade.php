<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-lg">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Pelaksanaan Stock Opname</h2>
            <p class="text-xs text-gray-500 mt-1">Audit kesesuaian jumlah stok fisik di gudang dengan catatan sistem (Buku Besar Stok).</p>
        </div>
    </div>

    <div class="p-6">
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Inisiasi Opname -->
        <form wire:submit.prevent="simpan" class="mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Pelaksanaan</label>
                    <input type="date" wire:model="tgl_opname" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    @error('tgl_opname') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi Gudang / Unit</label>
                    <select wire:model="id_unit" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id_unit }}">{{ $u->nama_unit }}</option>
                        @endforeach
                    </select>
                    @error('id_unit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4 flex justify-between items-end border-b border-gray-200 pb-3">
                <h3 class="text-md font-semibold text-gray-700">Rincian Perhitungan Fisik Barang</h3>
                <button type="button" wire:click="addItem" class="inline-flex items-center px-3 py-1.5 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg class="w-4 h-4 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Baris Barang
                </button>
            </div>

            <div class="space-y-4">
                @foreach($items as $index => $item)
                <div class="flex items-start space-x-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm transition-all hover:border-blue-300">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pilih Barang Master</label>
                        <select wire:model="items.{{ $index }}.id_barang" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}">{{ $b->nama_barang }} ({{ $b->satuan }})</option>
                            @endforeach
                        </select>
                        @error('items.'.$index.'.id_barang') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-40">
                        <label class="block text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Hasil Hitung Fisik</label>
                        <input type="number" wire:model="items.{{ $index }}.qty_fisik" class="w-full rounded-md border border-blue-300 bg-blue-50 px-3 py-2.5 text-blue-900 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
                        @error('items.'.$index.'.qty_fisik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6">
                        @if(count($items) > 1)
                        <button type="button" wire:click="removeItem({{ $index }})" class="p-2.5 bg-red-50 text-red-600 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Catatan Khusus Penyesuaian -->
            <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-md">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-700">
                        <strong>Auto-Adjustment Aktif:</strong> Jika terdapat perbedaan (selisih) antara hasil hitung fisik yang Anda inputkan di atas dengan stok yang ada di database saat ini, sistem akan otomatis melakukan mutasi penyesuaian untuk mengoreksinya.
                    </p>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-800 border border-transparent rounded-md shadow-sm text-sm font-bold text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all transform hover:scale-[1.02]">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Simpan Hasil Opname
                </button>
            </div>
        </form>

        <!-- Tabel Riwayat Opname Terakhir -->
        <div class="mt-12">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Riwayat Audit Terakhir</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border border-gray-200 rounded-lg">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Lokasi</th>
                            <th class="px-6 py-4">Nama Barang</th>
                            <th class="px-6 py-4 text-center">Stok Sistem</th>
                            <th class="px-6 py-4 text-center bg-blue-50 text-blue-800">Stok Fisik</th>
                            <th class="px-6 py-4 text-center">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($opnames as $o)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ date('d M Y', strtotime($o->tgl_opname)) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $o->unitKerja->nama_unit ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $o->barang->nama_barang ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center font-mono">{{ $o->qty_sistem }}</td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-blue-700 bg-blue-50/30">{{ $o->qty_fisik }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($o->qty_selisih < 0)
                                    <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded">{{ $o->qty_selisih }} (Hilang)</span>
                                @elseif($o->qty_selisih > 0)
                                    <span class="text-green-600 font-bold bg-green-50 px-2 py-1 rounded">+{{ $o->qty_selisih }} (Lebih)</span>
                                @else
                                    <span class="text-gray-500 bg-gray-100 px-2 py-1 rounded">Sesuai (0)</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada riwayat pelaksanaan Stock Opname.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
