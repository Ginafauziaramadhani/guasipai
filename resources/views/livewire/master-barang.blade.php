<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Kelola Master Data Barang</h2>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Form Tambah/Edit -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 xl:col-span-1 h-fit">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-lg font-semibold text-gray-700">{{ $id_barang ? 'Edit Barang' : 'Tambah Barang Baru' }}</h3>
                @if($id_barang)
                    <button type="button" wire:click="closeModal" class="text-xs text-gray-500 hover:text-gray-700 underline">Batal Edit</button>
                @endif
            </div>
            <form wire:submit.prevent="simpan">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Barang</label>
                        <select wire:model="kode_kategori" required class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="ELEKTRONIK">Elektronik</option>
                            <option value="FURNITURE">Furniture</option>
                            <option value="KENDARAAN">Kendaraan</option>
                            <option value="ALAT_TULIS">Alat Tulis Kantor (ATK)</option>
                            <option value="ALAT_KEAMANAN">Alat Keamanan / Safety</option>
                            <option value="SERAGAM">Seragam / Pakaian</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                        @error('kode_kategori') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Barang</label>
                        <input type="text" wire:model="nama_barang" required placeholder="Contoh: Laptop Asus ROG" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('nama_barang') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Barang</label>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" wire:model="tipe_barang" value="aset" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                <span class="ml-2 text-sm font-medium text-gray-700">Aset (Terdaftar)</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" wire:model="tipe_barang" value="habis_pakai" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                <span class="ml-2 text-sm font-medium text-gray-700">Habis Pakai</span>
                            </label>
                        </div>
                        @error('tipe_barang') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Spesifikasi (Opsional)</label>
                        <textarea wire:model="spesifikasi" rows="2" placeholder="Detail spesifikasi..." class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                        @error('spesifikasi') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 xl:col-span-2 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Barang & Material</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-4">Kategori</th>
                            <th class="px-4 py-4">Nama Barang</th>
                            <th class="px-4 py-4">Tipe</th>
                            <th class="px-4 py-4">Sisa Stok</th>
                            <th class="px-4 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($barangs as $b)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-gray-600 font-medium">{{ $b->kode_kategori }}</td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-800">{{ $b->nama_barang }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1" title="{{ $b->spesifikasi }}">{{ $b->spesifikasi ?: '-' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @if($b->tipe_barang === 'aset')
                                    <span class="bg-indigo-100 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full text-xs font-bold tracking-wide">ASET</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-xs font-bold tracking-wide">HABIS PAKAI</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-bold text-gray-700">
                                {{ $b->total_stok ?? 0 }}
                            </td>
                            <td class="px-4 py-4 text-center space-x-2">
                                <button wire:click="edit({{ $b->id_barang }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                                <button wire:click="delete({{ $b->id_barang }})" class="text-red-500 hover:text-red-700 font-medium" onclick="confirm('Yakin ingin menghapus barang ini?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 italic">Belum ada data barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
