<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pengembalian Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold mb-4">Informasi Peminjaman</h3>
                            <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                <p><span class="font-semibold w-32 inline-block">Peminjam:</span> {{ $peminjaman->user->name }}</p>
                                <p><span class="font-semibold w-32 inline-block">Alat:</span> {{ $peminjaman->alat->nama_alat }} ({{ $peminjaman->jumlah }})</p>
                                <p><span class="font-semibold w-32 inline-block">Tgl Pinjam:</span> {{ $peminjaman->tanggal_peminjaman->format('d M Y') }}</p>
                                <p><span class="font-semibold w-32 inline-block">Rencana Kembali:</span> {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</p>
                                <p><span class="font-semibold w-32 inline-block">Hari Ini:</span> {{ date('d M Y') }}</p>
                            </div>

                            <div class="mt-6 bg-red-50 p-4 rounded-lg border border-red-200">
                                <h4 class="font-bold text-red-700">Denda Keterlambatan</h4>
                                <div class="flex justify-between items-center mt-2">
                                    <span>Terlambat:</span>
                                    <span class="font-mono text-lg font-bold">{{ $terlambat_hari }} hari</span>
                                </div>
                                <div class="flex justify-between items-center mt-1 text-red-600 font-bold text-xl">
                                    <span>Biaya Denda:</span>
                                    <span>Rp {{ number_format($denda_keterlambatan, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-xs text-red-500 mt-2">* Rp 5.000 / hari</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold mb-4">Form Pengembalian</h3>
                             <form id="return-form" action="{{ route('petugas.monitoring.return', $peminjaman) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <x-input-label for="kondisi_akhir" :value="__('Kondisi Alat Saat Kembali')" />
                                    <select id="kondisi_akhir" name="kondisi_akhir" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required onchange="toggleDendaField()">
                                        <option value="baik">Baik</option>
                                        <option value="rusak_ringan">Rusak Ringan</option>
                                        <option value="rusak_berat">Rusak Berat</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('kondisi_akhir')" class="mt-2" />
                                </div>

                                <div id="denda_kerusakan_wrapper" class="mb-4" style="display: none;">
                                    <x-input-label for="denda_kerusakan" :value="__('Denda Kerusakan')" />
                                    <x-text-input id="denda_kerusakan" name="denda_kerusakan" type="number" class="block mt-1 w-full" value="0" min="0" required oninput="calculateTotal()" />
                                    <x-input-error :messages="$errors->get('denda_kerusakan')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="catatan" :value="__('Catatan Tambahan (Opsional)')" />
                                    <textarea id="catatan" name="catatan" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <div class="bg-gray-100 p-4 rounded-lg mb-6 border border-gray-200">
                                    <div class="flex justify-between items-center font-bold text-lg text-gray-800">
                                        <span>Total Denda Bayar:</span>
                                        <span id="total_bayar">Rp {{ number_format($denda_keterlambatan, 0, ',', '.') }}</span>
                                    </div>
                                    <div id="denda_detail" class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">
                                        * Denda Telat: Rp {{ number_format($denda_keterlambatan, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('petugas.monitoring.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">Batal</a>
                                    <button type="button" onclick="confirmSubmit('return-form', 'Selesaikan Pengembalian?', 'Pastikan kondisi alat dan jumlah denda yang diterima sudah benar.')" class="px-8 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-lg shadow-green-100">
                                        Simpan & Selesai
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDendaField() {
            const kondisi = document.getElementById('kondisi_akhir').value;
            const wrapper = document.getElementById('denda_kerusakan_wrapper');
            const inputDenda = document.getElementById('denda_kerusakan');
            
            if (kondisi === 'rusak_ringan' || kondisi === 'rusak_berat') {
                wrapper.style.display = 'block';
            } else {
                wrapper.style.display = 'none';
                inputDenda.value = 0;
            }
            calculateTotal();
        }

        function calculateTotal() {
            const dendaTelat = {{ $denda_keterlambatan }};
            const inputDendaRusak = document.getElementById('denda_kerusakan');
            const dendaRusak = parseInt(inputDendaRusak.value) || 0;
            
            const total = dendaTelat + dendaRusak;
            
            // Format Rupiah
            const formatted = new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(total);
            
            document.getElementById('total_bayar').textContent = formatted;

            // Update detail text
            let detailText = `* Denda Telat: Rp {{ number_format($denda_keterlambatan, 0, ',', '.') }}`;
            if (dendaRusak > 0) {
                detailText += ` + Denda Kerusakan: Rp ${new Intl.NumberFormat('id-ID').format(dendaRusak)}`;
            }
            document.getElementById('denda_detail').textContent = detailText;
        }
    </script>
</x-app-layout>
