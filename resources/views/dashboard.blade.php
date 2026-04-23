<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-6">

    @if(Auth::user()->role === 'peminjam')
        {{-- ========== DASHBOARD PEMINJAM ========== --}}

        <!-- Welcome + Stats Combined -->
        <div class="card-metronic overflow-hidden border-0 shadow-sm bg-white">
            <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            Selamat Datang, <span class="text-[#009ef7]">{{ Auth::user()->name }}</span>!
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Kelola peminjaman alat praktik dengan mudah.
                        </p>
                    </div>
                    @php
                        $cartItems = session()->get('cart', []);
                        $cartCount = array_sum(array_column($cartItems, 'jumlah'));
                    @endphp
                    <a href="{{ route('peminjam.cart.index') }}" class="relative group flex items-center gap-2 px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-sm hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Lihat Keranjang
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] border-2 border-white shadow-sm group-hover:scale-110 transition-transform cart-badge">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
            <!-- Stats Row -->
            <div class="grid grid-cols-3 border-t border-gray-100">
                <div class="p-4 flex items-center gap-3 border-r border-gray-100">
                    <div class="p-2.5 rounded-lg bg-blue-50 text-[#009ef7]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Pinjaman</p>
                        <p class="text-lg font-black text-gray-800">{{ number_format($stats['total_pinjaman']) }}</p>
                    </div>
                </div>
                <div class="p-4 flex items-center gap-3 border-r border-gray-100">
                    <div class="p-2.5 rounded-lg bg-yellow-50 text-yellow-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                        <p class="text-lg font-black text-gray-800">{{ number_format($stats['pending']) }}</p>
                    </div>
                </div>
                <div class="p-4 flex items-center gap-3">
                    <div class="p-2.5 rounded-lg bg-green-50 text-green-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
                        <p class="text-lg font-black text-gray-800">{{ number_format($stats['selesai']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alat Tersedia untuk Dipinjam -->
        <div class="card-metronic bg-white">
            <div class="p-6 pb-4 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-800">Alat Tersedia untuk Dipinjam</h3>
                <span class="text-xs text-gray-400">{{ $alatTersedia->count() }} alat siap pinjam</span>
            </div>

            @if($alatTersedia->count() > 0)
                <div class="px-6 pb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($alatTersedia as $alat)
                        <div class="group rounded-xl border border-gray-100 bg-white overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all duration-200 flex flex-col">
                            <div class="relative h-36 overflow-hidden bg-gray-50 shrink-0">
                                @if($alat->foto)
                                    <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-200">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                                <span class="absolute top-2 left-2 px-2 py-0.5 bg-white/90 text-gray-600 text-[9px] font-bold uppercase tracking-wider rounded-md">
                                    {{ $alat->kategori->nama_kategori ?? '-' }}
                                </span>
                                <span class="absolute top-2 right-2 px-2 py-0.5 bg-green-500 text-white text-[9px] font-bold rounded-md">
                                    {{ $alat->jumlah_tersedia }} unit
                                </span>
                            </div>
                            <div class="p-3 flex flex-col flex-1">
                                <h4 class="font-bold text-gray-900 text-sm truncate" title="{{ $alat->nama_alat }}">{{ $alat->nama_alat }}</h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $alat->merk ?? 'Tanpa Merk' }}</p>
                                <div class="mt-auto pt-3 flex items-center justify-between gap-2">
                                    <button onclick="addToCart({{ $alat->id }})" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-[#009ef7] hover:text-white transition shadow-sm group/btn shrink-0" title="Tambah ke Keranjang">
                                        <svg class="w-4 h-4 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </button>
                                    <a href="{{ route('peminjam.katalog.show', $alat) }}" class="flex items-center justify-center flex-1 px-3 py-2 bg-gray-900 text-white rounded-lg text-xs font-bold hover:bg-[#009ef7] transition">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 pb-6 pt-2 border-t border-gray-50">
                    <a href="{{ route('peminjam.katalog.index') }}" class="flex items-center justify-center w-full px-4 py-3 bg-gray-50 text-gray-700 rounded-xl text-sm font-bold hover:bg-[#009ef7] hover:text-white transition">
                        Lihat Semua Katalog &rarr;
                    </a>
                </div>
            @else
                <div class="px-6 pb-6 text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-3 bg-gray-50 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="font-bold text-gray-500">Belum Ada Alat Tersedia</p>
                    <p class="text-sm mt-1 text-gray-400">Semua alat sedang dalam masa peminjaman atau belum didaftarkan.</p>
                </div>
            @endif
        </div>

    @else
        {{-- ========== DASHBOARD ADMIN & PETUGAS ========== --}}

        <!-- Page Heading -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="text-sm text-gray-500">Live monitoring data inventaris dan keuangan denda.</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.alat.create') }}" class="px-5 py-2 bg-[#009ef7] text-white rounded-lg font-bold text-sm hover:bg-[#0086d1] transition shadow-sm">
                        + Tambah Alat
                    </a>
                @endif
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card-metronic p-6 bg-white border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#009ef7] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Alat</p>
                        <h3 class="text-xl font-black text-gray-800">{{ number_format($stats['total_alat']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-metronic p-6 bg-white border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                        <h3 class="text-xl font-black text-gray-800">{{ number_format($stats['pending']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-metronic p-6 bg-white border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
                        <h3 class="text-xl font-black text-gray-800">{{ number_format($stats['selesai']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-metronic p-6 bg-white border border-gray-100 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alat Rusak</p>
                        <h3 class="text-xl font-black text-gray-800">{{ number_format($stats['rusak']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance Section -->
        <div class="card-metronic bg-white overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
            <div class="p-8 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pendapatan Denda</p>
                        <h2 class="text-3xl font-black text-gray-900 leading-none">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</h2>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic">Akumulasi Real-time</span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
                    <a href="{{ route('admin.peminjaman.index') }}" class="px-6 py-3 bg-gray-50 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-100 transition text-center">Riwayat Transaksi</a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.alat.index') }}" class="px-6 py-3 bg-[#009ef7] text-white rounded-xl font-bold text-sm hover:bg-[#0086d1] transition shadow-md text-center">Manajemen Inventaris</a>
                    @endif
                </div>
            </div>
            <!-- <div class="bg-gray-50/50 px-8 py-3 border-t border-gray-100">
                <p class="text-[10px] text-gray-400 font-medium">Data diperbaharui otomatis setiap terjadi pengembalian alat dengan denda.</p>
            </div> -->
        </div>
        <!-- Recent Data Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Recent Loans -->
            <div class="card-metronic bg-white border border-gray-100 flex flex-col">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Peminjaman Terbaru</h3>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.peminjaman.index' : 'petugas.monitoring.index') }}" class="text-xs font-bold text-[#009ef7] hover:underline">Lihat Semua</a>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-bold">
                            <tr>
                                <th class="px-6 py-3">Peminjam</th>
                                <th class="px-6 py-3">Alat</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentLoans as $loan)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800">{{ $loan->user->name }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $loan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-medium">
                                        {{ $loan->alat->nama_alat }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-50 text-yellow-600',
                                                'dipinjam' => 'bg-blue-50 text-blue-600',
                                                'kembali' => 'bg-indigo-50 text-indigo-600',
                                                'selesai' => 'bg-green-50 text-green-600',
                                                'ditolak' => 'bg-red-50 text-red-600',
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase {{ $statusClasses[$loan->status] ?? 'bg-gray-50 text-gray-600' }}">
                                            {{ $loan->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Belum ada aktivitas peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Tools -->
            <div class="card-metronic bg-white border border-gray-100 flex flex-col">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Stok Alat Terbaru</h3>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.alat.index' : 'petugas.katalog.index') }}" class="text-xs font-bold text-[#009ef7] hover:underline">Lihat Semua</a>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-bold">
                            <tr>
                                <th class="px-6 py-3">Nama Alat</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Tersedia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentAlats as $alat)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-gray-50 flex items-center justify-center text-gray-400 overflow-hidden">
                                                @if($alat->foto)
                                                    <img src="{{ Storage::url($alat->foto) }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                @endif
                                            </div>
                                            <span class="font-bold text-gray-800 truncate max-w-[120px]">{{ $alat->nama_alat }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md font-medium">
                                            {{ $alat->kategori->nama_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-gray-800">{{ $alat->jumlah_tersedia }}</span>
                                            <span class="text-[10px] text-gray-400">/ {{ $alat->jumlah_total }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Data alat belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif

    </div>

    <!-- AJAX Add to Cart Logic (Dashboard) -->
    @if(Auth::user()->role === 'peminjam')
    <script>
        function addToCart(alatId) {
            fetch(`/peminjam/cart/${alatId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ jumlah: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    let cartBadges = document.querySelectorAll('.cart-badge');
                    cartBadges.forEach(badge => {
                        badge.textContent = data.cartCount;
                        badge.classList.remove('hidden');
                    });

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Barang berhasil dimasukkan ke keranjang.',
                        icon: 'success',
                        confirmButtonColor: '#009ef7',
                        confirmButtonText: 'Lanjut',
                        timer: 2000,
                        timerProgressBar: true,
                        customClass: {
                            title: 'text-lg font-bold text-gray-800',
                            confirmButton: 'px-6 py-2 rounded-md font-semibold text-white text-xs uppercase tracking-wider'
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#f1416c',
                        confirmButtonText: 'Tutup'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi masalah pada jaringan atau server kami.',
                    icon: 'warning',
                    confirmButtonColor: '#f1f1f1',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        confirmButton: 'text-gray-800 px-6 py-2 rounded-md font-semibold text-xs uppercase tracking-wider'
                    }
                });
            });
        }
    </script>
    @endif
</x-app-layout>
