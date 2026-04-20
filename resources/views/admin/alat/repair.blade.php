<x-app-layout>
    <x-slot name="header">
        Penyelesaian Perbaikan Alat
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card-metronic bg-white p-8">
                <div class="flex items-center mb-8">
                    <div class="w-16 h-16 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $alat->nama_alat }}</h3>
                        <p class="text-sm text-gray-400">Restorasi unit dari kategori Rusak ke Stok Tersedia</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-8 flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Stok Rusak Saat Ini</span>
                        <span class="text-3xl font-black text-red-600">{{ $alat->jumlah_rusak }} <span class="text-sm font-normal text-gray-400 uppercase">Unit</span></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Total Inventaris</span>
                        <span class="text-xl font-bold text-gray-800">{{ $alat->jumlah_total }} Unit</span>
                    </div>
                </div>

                <form action="{{ route('admin.alat.repair', $alat) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <x-input-label for="jumlah" :value="__('Jumlah Unit yang Berhasil Diperbaiki')" />
                        <div class="relative mt-1">
                            <x-text-input id="jumlah" name="jumlah" type="number" class="block w-full pl-4 pr-12 py-3" :value="old('jumlah', $alat->jumlah_rusak)" min="1" max="{{ $alat->jumlah_rusak }}" required />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-gray-400 font-bold text-xs uppercase">Unit</span>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 italic">* Unit yang direstore akan otomatis ditambahkan ke stok yang tersedia (Ready).</p>
                        <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.alat.index') }}" class="px-6 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit" class="px-8 py-3 bg-[#009ef7] text-white rounded-xl font-bold hover:bg-[#0086d1] transition shadow-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesaikan Perbaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
