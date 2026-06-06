<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Manajemen User</h2>
        <button wire:click="create()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow-sm transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah User Baru
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Data User -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'pimpinan')
                                <span class="bg-purple-100 text-purple-700 border border-purple-200 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Pimpinan</span>
                            @else
                                <span class="bg-blue-100 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <button wire:click="edit({{ $user->id }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                            @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" class="text-red-500 hover:text-red-700 font-medium ml-2" onclick="confirm('Yakin ingin menghapus user ini?') || event.stopImmediatePropagation()">Hapus</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Tidak ada data user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur-sm"></div>
            </div>

            <!-- Modal Panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            {{ $id_user ? 'Edit User' : 'Tambah User Baru' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                                <input type="text" wire:model="name" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" required>
                                @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                <input type="email" wire:model="email" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" required>
                                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                                <input type="password" wire:model="password" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" placeholder="{{ $id_user ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 8 karakter' }}" {{ $id_user ? '' : 'required' }}>
                                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role</label>
                                <select wire:model="role" class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" required>
                                    <option value="admin">Admin</option>
                                    <option value="pimpinan">Pimpinan</option>
                                </select>
                                @error('role') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
