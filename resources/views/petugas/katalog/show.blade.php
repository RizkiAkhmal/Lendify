<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Alat (View Only)') }}
            </h2>
            <a href="{{ route('petugas.katalog.index') }}" class="text-sm text-[#009ef7] hover:underline flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Image & Basic Info -->
                        <div class="md:w-1/3">
                            <div class="rounded-lg overflow-hidden shadow-lg mb-4">
                                @if($alat->foto)
                                    <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-auto object-cover">
                                @else
                                    <div class="w-full h-64 bg-gray-50 flex items-center justify-center text-gray-300">
                                        <svg class="h-20 w-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Ketersediaan Stok</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-gray-800">{{ $alat->jumlah_tersedia }} <span class="text-xs font-normal text-gray-400 uppercase">Unit</span></span>
                                    <span class="px-2 py-1 {{ $alat->jumlah_tersedia > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-[10px] font-bold uppercase rounded">
                                        {{ $alat->jumlah_tersedia > 0 ? 'Ready' : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="md:w-2/3">
                            <div class="mb-6">
                                <span class="text-sm font-bold text-[#009ef7] uppercase tracking-wider">{{ $alat->kategori->nama_kategori }}</span>
                                <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $alat->nama_alat }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Kode: {{ $alat->kode_alat }}</span>
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Merk: {{ $alat->merk ?? 'No Brand' }}</span>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 border-b pb-2">Spesifikasi Detail</h4>
                                <div class="prose max-w-none text-gray-600">
                                    {!! nl2br(e($alat->spesifikasi ?? 'Tidak ada spesifikasi detail untuk alat ini.')) !!}
                                </div>
                            </div>



                            <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-blue-700">
                                            <strong>Mode Lihat Saja:</strong> Sebagai petugas lab, Anda memiliki akses untuk melihat detail alat namun tidak dapat melakukan peminjaman atau perubahan data melalui halaman ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
