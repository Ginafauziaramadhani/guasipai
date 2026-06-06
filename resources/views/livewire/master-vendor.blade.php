<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Kelola Master Data Vendor</h2>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Tambah -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 lg:col-span-1 h-fit">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-lg font-semibold text-gray-700">{{ $id_vendor ? 'Edit Vendor' : 'Tambah Vendor Baru' }}</h3>
                @if($id_vendor)
                    <button type="button" wire:click="closeModal" class="text-xs text-gray-500 hover:text-gray-700 underline">Batal Edit</button>
                @endif
            </div>
            <form wire:submit.prevent="simpan">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Vendor/PT</label>
                        <input type="text" wire:model="nama_vendor" required placeholder="Contoh: PT Garda Abadi" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('nama_vendor') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea wire:model="alamat" required rows="3" placeholder="Contoh: Jl. Sudirman No. 1..." class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                        @error('alamat') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
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
        <div class="bg-white rounded-xl shadow-md border border-gray-100 lg:col-span-2 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Vendor Suplier</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nama Vendor</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($vendors as $v)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-500">#{{ $v->id_vendor }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $v->nama_vendor }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $v->alamat }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button wire:click="edit({{ $v->id_vendor }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                                <button wire:click="delete({{ $v->id_vendor }})" class="text-red-500 hover:text-red-700 font-medium" onclick="confirm('Yakin ingin menghapus vendor ini?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data vendor.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
