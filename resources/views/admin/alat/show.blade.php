<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Alat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="md:w-1/3">
                            @if($alat->foto)
                                <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-auto rounded-lg shadow-md object-cover">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                                    <svg class="h-20 w-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="md:w-2/3">
                            <h3 class="text-2xl font-bold mb-4">{{ $alat->nama_alat }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Kode Alat</p>
                                    <p class="font-semibold">{{ $alat->kode_alat }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Kategori</p>
                                    <p class="font-semibold">{{ $alat->kategori->nama_kategori }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Merk</p>
                                    <p class="font-semibold">{{ $alat->merk ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 text-sm">Jumlah Total</p>
                                    <p class="font-semibold">{{ $alat->jumlah_total }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Jumlah Tersedia</p>
                                    <p class="font-semibold {{ $alat->jumlah_tersedia > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $alat->jumlah_tersedia }}</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <p class="text-gray-600 text-sm">Spesifikasi</p>
                                <p class="text-gray-800 whitespace-pre-line">{{ $alat->spesifikasi ?? '-' }}</p>
                            </div>

                            <div class="mt-8 flex gap-4">
                                <a href="{{ route('admin.alat.edit', $alat) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Edit</a>
                                <a href="{{ route('admin.alat.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
