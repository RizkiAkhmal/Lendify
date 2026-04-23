<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Alat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.alat.update', $alat) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Kode Alat -->
                        <div>
                            <x-input-label for="kode_alat" :value="__('Kode Alat')" />
                            <x-text-input id="kode_alat" class="block mt-1 w-full" type="text" name="kode_alat" :value="old('kode_alat', $alat->kode_alat)" required autofocus />
                            <x-input-error :messages="$errors->get('kode_alat')" class="mt-2" />
                        </div>

                        <!-- Nama Alat -->
                        <div class="mt-4">
                            <x-input-label for="nama_alat" :value="__('Nama Alat')" />
                            <x-text-input id="nama_alat" class="block mt-1 w-full" type="text" name="nama_alat" :value="old('nama_alat', $alat->nama_alat)" required />
                            <x-input-error :messages="$errors->get('nama_alat')" class="mt-2" />
                        </div>

                        <!-- Kategori -->
                        <div class="mt-4">
                            <x-input-label for="kategori_id" :value="__('Kategori')" />
                            <select id="kategori_id" name="kategori_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($kategori as $cat)
                                    <option value="{{ $cat->id }}" {{ old('kategori_id', $alat->kategori_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
                        </div>

                        <!-- Merk -->
                        <div class="mt-4">
                            <x-input-label for="merk" :value="__('Merk')" />
                            <x-text-input id="merk" class="block mt-1 w-full" type="text" name="merk" :value="old('merk', $alat->merk)" />
                            <x-input-error :messages="$errors->get('merk')" class="mt-2" />
                        </div>

                        <!-- Spesifikasi -->
                        <div class="mt-4">
                            <x-input-label for="spesifikasi" :value="__('Spesifikasi')" />
                            <textarea id="spesifikasi" name="spesifikasi" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('spesifikasi', $alat->spesifikasi) }}</textarea>
                            <x-input-error :messages="$errors->get('spesifikasi')" class="mt-2" />
                        </div>



                        <!-- Jumlah Total -->
                        <div class="mt-4">
                            <x-input-label for="jumlah_total" :value="__('Jumlah Total')" />
                            <x-text-input id="jumlah_total" class="block mt-1 w-full" type="number" name="jumlah_total" :value="old('jumlah_total', $alat->jumlah_total)" required min="0" />
                            <p class="text-xs text-gray-500 mt-1">Mengubah total akan menyesuaikan stok tersedia secara otomatis.</p>
                            <x-input-error :messages="$errors->get('jumlah_total')" class="mt-2" />
                        </div>

                        <!-- Jumlah Rusak -->
                        <div class="mt-4">
                            <x-input-label for="jumlah_rusak" :value="__('Jumlah Unit Rusak')" />
                            <x-text-input id="jumlah_rusak" class="block mt-1 w-full" type="number" name="jumlah_rusak" :value="old('jumlah_rusak', $alat->jumlah_rusak)" required min="0" />
                            <p class="text-xs text-gray-400 mt-1 italic">Unit yang sedang dalam masa perbaikan.</p>
                            <x-input-error :messages="$errors->get('jumlah_rusak')" class="mt-2" />
                        </div>
                        
                        <!-- Current Available (Read-only for info) -->
                        <div class="mt-4">
                            <x-input-label for="jumlah_tersedia" :value="__('Stok Tersedia Saat Ini (Info)')" />
                            <x-text-input id="jumlah_tersedia" class="block mt-1 w-full bg-gray-50 border-gray-200 text-gray-500" type="number" :value="$alat->jumlah_tersedia" disabled />
                        </div>

                        <!-- Foto -->
                        <div class="mt-4">
                            <x-input-label for="foto" :value="__('Foto Alat')" />
                            @if($alat->foto)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($alat->foto) }}" alt="Current Photo" class="h-20 w-20 object-cover rounded-md">
                                </div>
                            @endif
                            <input id="foto" type="file" name="foto" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.alat.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button class="ml-4">
                                {{ __('Update Alat') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
