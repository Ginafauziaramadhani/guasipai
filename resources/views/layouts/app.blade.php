<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Logistik Satpam PT GUA') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (via CDN for rapid development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-800 text-white flex-shrink-0 flex flex-col transition-transform duration-300 shadow-xl fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0">
            <div class="h-16 flex items-center justify-center bg-gray-900 border-b border-gray-700 shadow-sm">
                <h1 class="text-xl font-bold tracking-wider text-blue-400">GARDA<span class="text-white">LOGISTIK</span></h1>
            </div>
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <!-- Menu Pimpinan & Admin -->
                    <li>
                        <a href="{{ Auth::check() ? route(Auth::user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.pimpinan') : '#' }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    
                    @if(Auth::check() && Auth::user()->role === 'pimpinan')
                    <li>
                        <a href="{{ route('laporan.dashboard') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('laporan.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Laporan
                        </a>
                    </li>
                    @endif

                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <!-- Menu Khusus Admin -->
                    <li class="pt-6 pb-2 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Master Data
                    </li>
                    <li>
                        <a href="{{ route('barang.index') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('barang.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Data Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('unit.index') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('unit.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Unit Kerja
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('personel.index') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('personel.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Data Personel
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vendor.index') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('vendor.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Data Vendor
                        </a>
                    </li>
                    <li class="pt-6 pb-2 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Transaksi & Aset
                    </li>
                    <li>
                        <a href="{{ route('pengadaan.create') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('pengadaan.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengadaan.*') ? 'text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            Pengadaan (Masuk)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('distribusi.create') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('distribusi.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('distribusi.*') ? 'text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            Distribusi (Keluar)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('aset.store') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('aset.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('aset.*') ? 'text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                            Registrasi Aset
                        </a>
                    <li>
                        <a href="{{ route('servis.index') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('servis.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('servis.*') ? 'text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Riwayat Servis
                        </a>
                    </li>
                    <li class="pt-6 pb-2 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Gudang & Audit
                    </li>
                    <li>
                        <a href="{{ route('opname.index') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('opname.*') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('opname.*') ? 'text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Stock Opname
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.unit') }}" class="flex items-center px-6 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('laporan.unit') ? 'bg-gray-700 text-white border-l-4 border-blue-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('laporan.unit') ? 'text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Aset Per Unit
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full h-screen overflow-hidden bg-gray-50">
            <!-- Top Navbar -->
            <header class="h-16 flex items-center justify-between px-4 md:px-8 bg-white border-b border-gray-200 shadow-sm z-10 w-full">
                <div class="flex items-center">
                    <button type="button" onclick="toggleSidebar()" class="mr-3 p-1.5 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="text-lg md:text-xl font-bold text-gray-800 tracking-tight truncate">Logistik Satpam PT GUA</span>
                </div>
                <div class="flex items-center space-x-6">
                    @if(Auth::check())
                        <div class="flex items-center space-x-3 md:space-x-4">
                            <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 text-white flex items-center justify-center font-bold shadow-inner flex-shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:flex flex-col">
                                <span class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500 font-medium leading-tight">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>
                        </div>
                        <div class="h-6 w-px bg-gray-200 ml-3 md:ml-0"></div>
                        <form method="POST" action="{{ route('logout') }}" class="inline ml-3 md:ml-0">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold flex items-center transition-colors">
                                <svg class="w-5 h-5 md:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span class="hidden md:inline">Logout</span>
                            </button>
                        </form>
                    @endif
                </div>
            </header>

            <!-- Content Slot -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-8">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
    @stack('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
