<x-app-layout>
    <x-slot name="header">
        Katalog Alat Praktik
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Filter Card -->
        <div class="card-metronic p-6 bg-white">
            <form method="GET" action="{{ route('peminjam.katalog.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari nama alat, merk, atau spesifikasi..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#009ef7] transition" value="{{ request('search') }}">
                </div>
                
                <select name="kategori" class="w-full md:w-64 px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#009ef7] transition cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#009ef7] text-white rounded-xl font-bold hover:bg-[#0086d1] transition shadow-md flex items-center justify-center">
                    Filter
                </button>
            </form>
        </div>

        <!-- Grid Alat -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($alats as $alat)
                <div class="card-metronic bg-white group overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative h-56">
                        @if($alat->foto)
                            <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-300">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">No Image</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            @if($alat->jumlah_tersedia > 0)
                                <span class="px-3 py-1 bg-green-500 text-white text-[10px] font-black uppercase tracking-wider rounded-lg shadow-lg">
                                    Tersedia
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white text-[10px] font-black uppercase tracking-wider rounded-lg shadow-lg">
                                    Habis
                                </span>
                            @endif
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-white">
                                {{ $alat->kategori->nama_kategori }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#009ef7] transition truncate" title="{{ $alat->nama_alat }}">
                                {{ $alat->nama_alat }}
                            </h3>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">{{ $alat->merk ?? 'Tanpa Merk' }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-50">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Tersedia</span>
                                <span class="text-xl font-black text-gray-900">{{ $alat->jumlah_tersedia }} <span class="text-xs font-normal text-gray-400 uppercase">Unit</span></span>
                            </div>
                            
                            @if($alat->jumlah_tersedia > 0)
                                <a href="{{ route('peminjam.katalog.show', $alat) }}" class="px-5 py-2.5 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-[#009ef7] transition shadow-sm">
                                    Detail
                                </a>
                            @else
                                <button disabled class="px-5 py-2.5 bg-gray-100 text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed">
                                    Kosong
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 bg-white rounded-2xl shadow-sm text-center">
                    <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"/></svg>
                    <h3 class="text-xl font-bold text-gray-800">Alat Tidak Ditemukan</h3>
                    <p class="text-gray-400 mt-2">Cari kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        @if($alats->hasPages())
            <div class="py-10">
                {{ $alats->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
