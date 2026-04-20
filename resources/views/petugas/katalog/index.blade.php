<x-app-layout>
    <x-slot name="header">
        Katalog Alat (View Only)
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Filter Card -->
        <div class="card-metronic p-6 bg-white">
            <form method="GET" action="{{ route('petugas.katalog.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari nama alat atau merk..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#009ef7] transition" value="{{ request('search') }}">
                </div>
                
                <select name="kategori" class="w-full md:w-64 px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#009ef7] transition cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-gray-800 transition shadow-md">
                    Filter
                </button>
            </form>
        </div>

        <!-- Grid Alat -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($alats as $alat)
                <div class="card-metronic bg-white overflow-hidden group">
                    <div class="relative h-48 bg-gray-50">
                        @if($alat->foto)
                            <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[8px] font-black uppercase tracking-widest">No Image</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 {{ $alat->jumlah_tersedia > 0 ? 'bg-green-500' : 'bg-red-500' }} text-white text-[9px] font-bold uppercase rounded-md shadow-sm">
                                {{ $alat->jumlah_tersedia > 0 ? 'Ready' : 'Out' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="mb-3">
                            <span class="text-[9px] font-bold text-[#009ef7] uppercase tracking-wider">{{ $alat->kategori->nama_kategori }}</span>
                            <h3 class="text-sm font-bold text-gray-900 truncate" title="{{ $alat->nama_alat }}">
                                {{ $alat->nama_alat }}
                            </h3>
                        </div>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                            <div>
                                <span class="text-[9px] font-bold text-gray-400 uppercase leading-none block mb-1">Stok</span>
                                <span class="text-lg font-black text-gray-800">{{ $alat->jumlah_tersedia }}</span>
                            </div>
                            <a href="{{ route('petugas.katalog.show', $alat) }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-[11px] font-bold hover:bg-gray-200 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400 italic">No data found.</div>
            @endforelse
        </div>

        @if($alats->hasPages())
            <div class="mt-6">
                {{ $alats->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
