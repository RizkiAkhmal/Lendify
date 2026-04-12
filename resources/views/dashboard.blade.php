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
                <div>
                    <h1 class="text-xl font-bold text-gray-900">
                        Selamat Datang, <span class="text-[#009ef7]">{{ Auth::user()->name }}</span>!
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Anda masuk sebagai <span class="font-semibold text-gray-700 capitalize">{{ Auth::user()->role }}</span>. Kelola peminjaman alat praktik dengan mudah.
                    </p>
                </div>
            </div>
            <!-- Stats Row -->
            <div class="grid grid-cols-3 border-t border-gray-100">
                <div class="p-4 flex items-center gap-3 border-r border-gray-100">
                    <div class="p-2.5 rounded-lg bg-blue-50 text-[#009ef7]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Alat</p>
                        <p class="text-lg font-black text-gray-800">{{ number_format($stats['total_alat']) }}</p>
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
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $alat->merk ?? 'Tanpa Merk' }} &middot; <span class="capitalize">{{ str_replace('_', ' ', $alat->kondisi) }}</span></p>
                                <div class="mt-auto pt-3">
                                    <a href="{{ route('peminjam.katalog.show', $alat) }}" class="flex items-center justify-center w-full px-3 py-2 bg-gray-900 text-white rounded-lg text-xs font-bold hover:bg-[#009ef7] transition">
                                        Pinjam
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
        {{-- ========== DASHBOARD ADMIN & PETUGAS (ORIGINAL) ========== --}}

        <!-- Welcome Card -->
        <div class="card-metronic overflow-hidden border-0 shadow-sm">
            <div class="p-8 flex flex-col md:flex-row items-center justify-between bg-white relative overflow-hidden">
                <div class="z-10 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">
                        Selamat Datang, <span class="text-[#009ef7]">{{ Auth::user()->name }}</span>!
                    </h1>
                    <p class="text-gray-500 max-w-lg">
                        Anda masuk sebagai <span class="font-semibold text-gray-700 capitalize">{{ Auth::user()->role }}</span>. 
                        Aplikasi ini dikembangkan untuk memudahkan manajemen peminjaman alat praktik secara efisien dan transparan.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3 justify-center md:justify-start">
                        @if(Auth::user()->role === 'petugas')
                            <a href="{{ route('petugas.approval.index') }}" class="px-6 py-2 bg-[#009ef7] text-white rounded-lg font-bold hover:bg-[#0086d1] transition shadow-sm">
                                Cek Antrean Persetujuan
                            </a>
                        @else
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-[#009ef7] text-white rounded-lg font-bold hover:bg-[#0086d1] transition shadow-sm">
                                Kelola Pengguna
                            </a>
                        @endif
                    </div>
                </div>
                <div class="mt-8 md:mt-0 opacity-20 md:opacity-100 z-0 md:relative">
                    <svg class="w-48 h-48 text-[#009ef7]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" />
                        <path d="M2 17L12 22L22 17" opacity="0.3" />
                        <path d="M2 12L12 17L22 12" opacity="0.5" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card-metronic p-6 flex items-center">
                <div class="p-4 rounded-xl bg-blue-50 text-[#009ef7] mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Alat</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['total_alat']) }}</h3>
                </div>
            </div>
            
            <div class="card-metronic p-6 flex items-center">
                <div class="p-4 rounded-xl bg-yellow-50 text-yellow-500 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['pending']) }}</h3>
                </div>
            </div>

            <div class="card-metronic p-6 flex items-center">
                <div class="p-4 rounded-xl bg-green-50 text-green-500 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['selesai']) }}</h3>
                </div>
            </div>

            <div class="card-metronic p-6 flex items-center">
                <div class="p-4 rounded-xl bg-red-50 text-red-500 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Rusak</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['rusak']) }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Latest Activity -->
            <div class="card-metronic p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Terakhir</h3>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.log.index') }}" class="text-sm font-bold text-[#009ef7] hover:underline">Lihat Semua</a>
                    @endif
                </div>
                <div class="space-y-6">
                    @forelse($latestActivities as $activity)
                        <div class="flex items-start">
                            <div class="w-2 h-2 rounded-full bg-[#009ef7] mt-2 mr-4"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800 font-semibold mb-1">
                                    @if($activity instanceof \App\Models\LogAktivitas)
                                        <span class="text-gray-900">{{ $activity->user->name }}</span> 
                                        melakukan <span class="text-[#009ef7]">{{ $activity->aksi }}</span> 
                                        pada tabel <span class="capitalize">{{ $activity->tabel }}</span>
                                    @else
                                        <span class="text-gray-900">{{ $activity->user->name }}</span> 
                                        meminjam <span class="text-[#009ef7]">{{ $activity->alat->nama_alat }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-sm italic">
                            Belum ada aktivitas tercatat.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Resource Center -->
            <div class="card-metronic p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Panduan Penggunaan</h3>
                </div>
                <div class="space-y-4">
                    <div class="p-4 rounded-xl border border-dashed border-gray-300 flex items-center hover:bg-gray-50 cursor-pointer transition">
                        <div class="p-3 rounded-lg bg-orange-50 text-orange-500 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Prosedur Peminjaman</p>
                            <p class="text-xs text-gray-500">Pelajari langkah demi langkah meminjam alat.</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border border-dashed border-gray-300 flex items-center hover:bg-gray-50 cursor-pointer transition">
                        <div class="p-3 rounded-lg bg-purple-50 text-purple-500 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Kebijakan Denda</p>
                            <p class="text-xs text-gray-500">Informasi tentang denda keterlambatan dan kerusakan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

    </div>
</x-app-layout>
