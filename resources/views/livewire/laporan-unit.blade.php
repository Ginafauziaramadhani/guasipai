<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Inventaris & Aset Per Unit Kerja</h2>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="max-w-md">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Unit Kerja / Cabang</label>
            <div class="relative">
                <select wire:model.live="id_unit_selected" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none bg-gray-50 shadow-sm transition-colors">
                    <option value="">-- Silakan Pilih Unit Kerja --</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id_unit }}">{{ $u->nama_unit }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Pilih unit untuk melihat daftar aset berseri dan riwayat barang yang pernah diterima.</p>
        </div>
    </div>

    @if($id_unit_selected)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Tabel Aset Fisik Berseri -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-blue-50 flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Aset Fisik (KTP Aset)</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Nama Aset</th>
                            <th class="px-6 py-4">Serial Number</th>
                            <th class="px-6 py-4 text-right">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($asets as $a)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $a->barang->nama_barang ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-gray-600">
                                <span class="bg-gray-100 px-2 py-1 rounded border border-gray-200">{{ $a->serial_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($a->status_kondisi == 'Tersedia')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Bagus</span>
                                @elseif($a->status_kondisi == 'Rusak')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Rusak</span>
                                @elseif($a->status_kondisi == 'Servis')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Servis</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $a->status_kondisi }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">Tidak ada aset bernomor seri yang dialokasikan di unit ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Riwayat Distribusi Kuantitas -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-green-50 flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Total Akumulasi Barang Diterima</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Nama Barang</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-right">Total Diterima</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($histori_distribusi as $h)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $h->nama_barang }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $h->kategori }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-green-700">
                                {{ $h->total_diterima }} <span class="text-xs font-normal text-gray-500 ml-1">{{ $h->satuan }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">Belum ada riwayat penerimaan barang dari gudang pusat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 p-4 border-t border-gray-100 text-xs text-gray-500">
                *Tabel di atas menampilkan total historis barang (habis pakai & aset) yang pernah didistribusikan dari gudang ke unit ini, mengabaikan barang yang sudah dipakai/rusak.
            </div>
        </div>

    </div>
    @endif
</div>
