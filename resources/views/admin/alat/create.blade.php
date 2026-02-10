<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Alat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.alat.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Kode Alat -->
                        <div>
                            <x-input-label for="kode_alat" :value="__('Kode Alat')" />
                            <x-text-input id="kode_alat" class="block mt-1 w-full" type="text" name="kode_alat" :value="old('kode_alat')" required autofocus />
                            <x-input-error :messages="$errors->get('kode_alat')" class="mt-2" />
                        </div>

                        <!-- Nama Alat -->
                        <div class="mt-4">
                            <x-input-label for="nama_alat" :value="__('Nama Alat')" />
                            <x-text-input id="nama_alat" class="block mt-1 w-full" type="text" name="nama_alat" :value="old('nama_alat')" required />
                            <x-input-error :messages="$errors->get('nama_alat')" class="mt-2" />
                        </div>

                        <!-- Kategori -->
                        <div class="mt-4">
                            <x-input-label for="kategori_id" :value="__('Kategori')" />
                            <select id="kategori_id" name="kategori_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Kategori</option>
                                @foreach($kategori as $cat)
                                    <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
                        </div>

                        <!-- Merk -->
                        <div class="mt-4">
                            <x-input-label for="merk" :value="__('Merk')" />
                            <x-text-input id="merk" class="block mt-1 w-full" type="text" name="merk" :value="old('merk')" />
                            <x-input-error :messages="$errors->get('merk')" class="mt-2" />
                        </div>

                        <!-- Spesifikasi -->
                        <div class="mt-4">
                            <x-input-label for="spesifikasi" :value="__('Spesifikasi')" />
                            <textarea id="spesifikasi" name="spesifikasi" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('spesifikasi') }}</textarea>
                            <x-input-error :messages="$errors->get('spesifikasi')" class="mt-2" />
                        </div>

                        <!-- Kondisi -->
                        <div class="mt-4">
                            <x-input-label for="kondisi" :value="__('Kondisi')" />
                            <select id="kondisi" name="kondisi" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                            <x-input-error :messages="$errors->get('kondisi')" class="mt-2" />
                        </div>

                        <!-- Jumlah Total -->
                        <div class="mt-4">
                            <x-input-label for="jumlah_total" :value="__('Jumlah Total')" />
                            <x-text-input id="jumlah_total" class="block mt-1 w-full" type="number" name="jumlah_total" :value="old('jumlah_total')" required min="1" />
                            <x-input-error :messages="$errors->get('jumlah_total')" class="mt-2" />
                        </div>

                        <!-- Foto -->
                        <div class="mt-4">
                            <x-input-label for="foto" :value="__('Foto Alat')" />
                            <input id="foto" type="file" name="foto" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.alat.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button class="ml-4">
                                {{ __('Create Alat') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
