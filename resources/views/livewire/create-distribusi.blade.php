<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-lg">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Form Distribusi (Barang Keluar)</h2>
            <p class="text-xs text-gray-500 mt-1">Gunakan form ini untuk mendistribusikan barang ke unit dan personel tertentu.</p>
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

        <form wire:submit.prevent="simpan">
            <!-- Header Distribusi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Unit Tujuan</label>
                    <select wire:model="id_unit" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">-- Pilih Unit Tujuan --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id_unit }}">{{ $u->nama_unit }}</option>
                        @endforeach
                    </select>
                    @error('id_unit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Personel Penerima</label>
                    <select wire:model="id_personel_penerima" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">-- Pilih Personel --</option>
                        @foreach($personels as $p)
                            <option value="{{ $p->id_personel }}">{{ $p->nama_personel }} ({{ $p->jabatan }})</option>
                        @endforeach
                    </select>
                    @error('id_personel_penerima') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Distribusi</label>
                    <input type="date" wire:model="tgl_distribusi" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('tgl_distribusi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Rincian Barang -->
            <div class="mb-4 flex justify-between items-end border-b border-gray-200 pb-3">
                <h3 class="text-md font-semibold text-gray-700">Rincian Barang yang Dikeluarkan</h3>
                <button type="button" wire:click="addItem" class="inline-flex items-center px-3 py-1.5 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg class="w-4 h-4 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Baris
                </button>
            </div>

            @error('items') <span class="text-red-500 text-xs mb-4 block">{{ $message }}</span> @enderror

            <div class="space-y-4">
                @foreach($items as $index => $item)
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm transition-all">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pilih Barang</label>
                        <select wire:model="items.{{ $index }}.id_barang" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}">{{ $b->nama_barang }} ({{ $b->satuan }})</option>
                            @endforeach
                        </select>
                        @error('items.'.$index.'.id_barang') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-32">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Qty Keluar</label>
                        <input type="number" wire:model="items.{{ $index }}.qty_keluar" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" min="1">
                        @error('items.'.$index.'.qty_keluar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6">
                        @if(count($items) > 1)
                        <button type="button" wire:click="removeItem({{ $index }})" class="p-2.5 bg-red-50 text-red-600 border border-red-200 rounded-md hover:bg-red-100 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500" title="Hapus Baris">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @else
                        <button type="button" disabled class="p-2.5 bg-gray-100 text-gray-400 border border-gray-200 rounded-md cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Perhatian:</strong> Sistem akan otomatis menolak penyimpanan jika <em>Qty Keluar</em> melebihi ketersediaan stok fisik barang yang ada di gudang.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-8 flex justify-end border-t border-gray-100 pt-5">
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Transaksi Distribusi
                </button>
            </div>
        </form>
    </div>
</div>
