<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Alat') }}
        </h2>
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
                                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">
                                        <svg class="h-20 w-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Status Ketersediaan</p>
                                @if($alat->jumlah_tersedia > 0)
                                    <div class="flex items-center text-green-600 font-bold text-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tersedia ({{ $alat->jumlah_tersedia }} unit)
                                    </div>
                                @else
                                    <div class="flex items-center text-red-600 font-bold text-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Habis / Tidak Tersedia
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Details & Form -->
                        <div class="md:w-2/3">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $alat->nama_alat }}</h3>
                            <div class="flex items-center gap-4 mb-6">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">{{ $alat->kategori->nama_kategori }}</span>
                                <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">Merk: {{ $alat->merk ?? '-' }}</span>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-lg font-semibold mb-2">Spesifikasi</h4>
                                <p class="text-gray-700 whitespace-pre-line bg-gray-50 p-4 rounded-md">{{ $alat->spesifikasi ?? 'Tidak ada spesifikasi detail.' }}</p>
                            </div>

                            @if($alat->jumlah_tersedia > 0)
                                <div class="border-t pt-6 mt-6">
                                    <h4 class="text-xl font-bold mb-4">Tambahkan ke Keranjang</h4>
                                    
                                    @if(session('success'))
                                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if(session('error'))
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <div class="relative py-4 mb-2">
                                         <div class="absolute inset-0 flex items-center">
                                             <div class="w-full border-t border-gray-200"></div>
                                         </div>
                                         <div class="relative flex justify-center">
                                             <span class="bg-white px-4 text-xs text-gray-400 uppercase font-bold tracking-widest">FORMULIR PEMINJAMAN</span>
                                         </div>
                                     </div>

                                     <form id="loan-request-form" action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                                         @csrf
                                         <input type="hidden" name="alat_id" value="{{ $alat->id }}">

                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                             <!-- Tanggal Pinjam -->
                                             <div>
                                                 <x-input-label for="tanggal_peminjaman" :value="__('Tanggal Pinjam')" />
                                                 <x-text-input id="tanggal_peminjaman" class="block mt-1 w-full" type="date" name="tanggal_peminjaman" :value="old('tanggal_peminjaman', date('Y-m-d'))" min="{{ date('Y-m-d') }}" required />
                                                 <x-input-error :messages="$errors->get('tanggal_peminjaman')" class="mt-2" />
                                             </div>

                                             <!-- Tanggal Kembali -->
                                             <div>
                                                 <x-input-label for="tanggal_kembali_rencana" :value="__('Rencana Kembali')" />
                                                 <x-text-input id="tanggal_kembali_rencana" class="block mt-1 w-full" type="date" name="tanggal_kembali_rencana" :value="old('tanggal_kembali_rencana', date('Y-m-d', strtotime('+1 day')))" min="{{ date('Y-m-d') }}" required />
                                                 <x-input-error :messages="$errors->get('tanggal_kembali_rencana')" class="mt-2" />
                                             </div>

                                             <!-- Jumlah -->
                                             <div>
                                                 <x-input-label for="jumlah" :value="__('Jumlah Unit')" />
                                                 <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah', 1)" min="1" max="{{ $alat->jumlah_tersedia }}" required />
                                                 <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-wider">Tersedia: {{ $alat->jumlah_tersedia }} unit</p>
                                                 <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                                             </div>
                                         </div>

                                         <!-- Keperluan -->
                                         <div class="mt-4">
                                             <x-input-label for="keperluan" :value="__('Keperluan Peminjaman')" />
                                             <textarea id="keperluan" name="keperluan" rows="3" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required placeholder="Isi keperluan peminjaman alat.">{{ old('keperluan') }}</textarea>
                                             <x-input-error :messages="$errors->get('keperluan')" class="mt-2" />
                                         </div>

                                         <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                            <button type="button" onclick="addToCartFromDetail()" class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-xl font-bold text-sm text-indigo-600 uppercase tracking-widest hover:bg-indigo-100 transition shadow-sm">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                + Keranjang
                                            </button>
                                            <button type="button" onclick="confirmSubmit('loan-request-form', 'Ajukan Peminjaman?', 'Pastikan jumlah dan tanggal kembali sudah sesuai.')" class="flex-[2] inline-flex items-center justify-center px-4 py-3 bg-[#1e1e2d] border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-black transition shadow-lg">
                                                {{ __('Pinjam Sekarang') }}
                                            </button>
                                         </div>
                                         <p class="text-[10px] text-gray-400 mt-3 text-center font-medium">Pengajuan akan direview oleh petugas sebelum disetujui.</p>
                                    </form>
                                </div>

                                </div>
                            @else
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Maaf, alat ini sedang tidak tersedia untuk dipinjam saat ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tglPinjam = document.getElementById('tanggal_peminjaman');
            const tglKembali = document.getElementById('tanggal_kembali_rencana');

            // Set min tgl pinjam to today
            const today = new Date().toISOString().split('T')[0];
            tglPinjam.min = today;

            tglPinjam.addEventListener('change', function() {
                // Set min tgl kembali to be same as tgl pinjam
                tglKembali.min = this.value;
                
                // If current tgl kembali is less than new min, reset it
                if (tglKembali.value < this.value) {
                    tglKembali.value = this.value;
                }
            });

            // Initial trigger
            if(tglPinjam.value) {
                tglKembali.min = tglPinjam.value;
            }
        });

        function addToCartFromDetail() {
            const jumlahInput = document.getElementById('jumlah');
            const alatId = {{ $alat->id }};
            const jumlah = jumlahInput.value;

            if (jumlah < 1) {
                Swal.fire({
                    title: 'Jumlah tidak valid',
                    text: 'Jumlah minimal peminjaman adalah 1 unit.',
                    icon: 'error',
                    confirmButtonColor: '#1e1e2d'
                });
                return;
            }

            fetch(`/peminjam/cart/${alatId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ jumlah: jumlah })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Barang berhasil dimasukkan ke keranjang.',
                        icon: 'success',
                        confirmButtonColor: '#009ef7',
                        confirmButtonText: 'Lanjut',
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#f1416c'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi masalah pada jaringan atau server kami.',
                    icon: 'warning',
                    confirmButtonColor: '#f1f1f1'
                });
            });
        }
    </script>
</x-app-layout>
