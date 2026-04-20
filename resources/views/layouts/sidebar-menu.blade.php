<div class="space-y-1">
    <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- Admin Section -->
    @if(Auth::user()->role === 'admin')
        <div class="pt-4 pb-2">
            <span class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Manajemen Data</span>
        </div>
        <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Data Pengguna</span>
        </a>
        <a href="{{ route('admin.kategori.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span>Kategori Alat</span>
        </a>
        <a href="{{ route('admin.alat.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
            <span>Data Alat</span>
        </a>

        <div class="pt-4 pb-2">
            <span class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Transaksi</span>
        </div>
        <a href="{{ route('admin.peminjaman.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            <span>Peminjaman</span>
        </a>
        <a href="{{ route('admin.pengembalian.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
            </svg>
            <span>Pengembalian</span>
        </a>
        <a href="{{ route('admin.log.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Audit Log</span>
        </a>
    @endif

    <!-- Petugas Section -->
    @if(Auth::user()->role === 'petugas')
        <div class="pt-4 pb-2">
            <span class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Operasional</span>
        </div>
        <a href="{{ route('petugas.katalog.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('petugas.katalog.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span>Katalog Barang</span>
        </a>
        <a href="{{ route('petugas.approval.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('petugas.approval.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Persetujuan</span>
        </a>
        <a href="{{ route('petugas.monitoring.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('petugas.monitoring.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>Monitoring</span>
        </a>
        <a href="{{ route('petugas.laporan.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Laporan</span>
        </a>
    @endif

    <!-- Peminjam Section -->
    @if(Auth::user()->role === 'peminjam')
        <div class="pt-4 pb-2">
            <span class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Peminjaman</span>
        </div>
        <a href="{{ route('peminjam.katalog.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('peminjam.katalog.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span>Katalog Alat</span>
        </a>
        <a href="{{ route('peminjam.peminjaman.index') }}" class="nav-link flex items-center px-4 py-3 text-sm rounded-lg {{ request()->routeIs('peminjam.peminjaman.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Riwayat Saya</span>
        </a>
    @endif
</div>
