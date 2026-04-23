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
                    <form method="POST" action="{{ route('admin.alat.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        @if($errors->any())
                        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center animate-shake">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-bold">
                                    Mohon lengkapi seluruh data alat. Ada beberapa field yang masih kosong atau tidak sesuai.
                                </p>
                            </div>
                        </div>
                        @endif

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



                        <!-- Jumlah Total -->
                        <div class="mt-4">
                            <x-input-label for="jumlah_total" :value="__('Jumlah Total')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                            <x-text-input id="jumlah_total" class="block mt-1 w-full" type="number" name="jumlah_total" :value="old('jumlah_total')" required min="1" placeholder="jumlah stok" :isError="$errors->has('jumlah_total')" />
                            <x-input-error :messages="$errors->get('jumlah_total')" class="mt-2" />
                        </div>



                        <!-- Foto -->
                        <div class="mt-4" x-data="{ photoName: null, photoPreview: null }">
                            <x-input-label for="foto" :value="__('Foto Alat')" />
                            
                            <!-- Custom File Upload Container -->
                            <div class="mt-2 flex items-center justify-center w-full">
                                <label for="foto" class="flex flex-col items-center justify-center w-full h-44 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all relative overflow-hidden group">
                                    
                                    <!-- Placeholder / Icon -->
                                    <template x-if="!photoPreview">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-4 text-gray-400 group-hover:text-[#009ef7] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-bold">Klik untuk unggah</span> atau seret foto</p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">PNG, JPG atau JPEG (Max 2MB)</p>
                                        </div>
                                    </template>

                                    <!-- Image Preview -->
                                    <template x-if="photoPreview">
                                        <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat" 
                                             :style="'background-image: url(\'' + photoPreview + '\');'">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <span class="text-white text-xs font-bold uppercase tracking-widest">Ubah Foto</span>
                                            </div>
                                        </div>
                                    </template>

                                    <input id="foto" type="file" name="foto" class="hidden" 
                                           accept="image/*"
                                           @change="
                                                photoName = $event.target.files[0].name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    photoPreview = e.target.result;
                                                };
                                                reader.readAsDataURL($event.target.files[0]);
                                           " />
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.alat.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition mr-6">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-[#1e1e2d] text-white rounded-lg font-bold hover:bg-black transition shadow-lg shadow-gray-200">
                                {{ __('Simpan Barang') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
