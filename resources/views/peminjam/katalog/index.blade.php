<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Alat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Search & Filter -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('peminjam.katalog.index') }}" class="flex flex-col md:flex-row gap-4">
                            <input type="text" name="search" placeholder="Cari alat..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 flex-grow" value="{{ request('search') }}">
                            <select name="kategori" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Cari</button>
                        </form>
                    </div>

                    <!-- Alat Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($alats as $alat)
                            <div class="border rounded-lg overflow-hidden hover:shadow-lg transition duration-200">
                                <div class="h-48 bg-gray-200 overflow-hidden relative">
                                    @if($alat->foto)
                                        <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    @if($alat->jumlah_tersedia > 0)
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">Tersedia: {{ $alat->jumlah_tersedia }}</span>
                                    @else
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Habis</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-1 truncate">{{ $alat->nama_alat }}</h3>
                                    <p class="text-gray-500 text-sm mb-2">{{ $alat->kategori->nama_kategori }}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('peminjam.katalog.show', $alat) }}" class="text-blue-600 hover:text-blue-800 font-medium">Lihat Detail</a>
                                        @if($alat->jumlah_tersedia > 0)
                                            <a href="{{ route('peminjam.katalog.show', $alat) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Pinjam</a>
                                        @else
                                            <button disabled class="bg-gray-300 text-gray-500 px-3 py-1 rounded cursor-not-allowed text-sm">Habis</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10 text-gray-500">
                                Tidak ada alat ditemukan.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $alats->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
