<div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 mt-4">
    <div class="flex items-center space-x-4 mb-4">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, Admin Logistik!</h2>
    </div>
    <p class="text-gray-600 leading-relaxed max-w-3xl">Gunakan menu navigasi di sebelah kiri untuk mengelola master data, melakukan registrasi aset, dan memproses transaksi seperti pengadaan serta distribusi barang. Anda memiliki akses penuh ke seluruh fitur sistem.</p>
    
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('pengadaan.create') }}" class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition-colors group">
            <div class="w-10 h-10 bg-white shadow-sm rounded-full flex items-center justify-center text-blue-500 group-hover:text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold text-gray-800 group-hover:text-blue-700">Mulai Transaksi Pengadaan</p>
                <p class="text-xs text-gray-500">Catat penerimaan barang masuk baru dari vendor.</p>
            </div>
        </a>
    </div>
</div>
