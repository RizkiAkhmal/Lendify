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
                            <form action="{{ route('petugas.monitoring.return', $peminjaman) }}" method="POST" onsubmit="return confirm('Proses pengembalian ini?')">
                                @csrf
                                
                                <div class="mb-4">
                                    <x-input-label for="kondisi_akhir" :value="__('Kondisi Alat Saat Kembali')" />
                                    <select id="kondisi_akhir" name="kondisi_akhir" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required onchange="calculateTotal()">
                                        <option value="baik" data-denda="0">Baik (Tanpa Denda)</option>
                                        <option value="rusak_ringan" data-denda="50000">Rusak Ringan (Denda Rp 50.000)</option>
                                        <option value="rusak_berat" data-denda="200000">Rusak Berat (Denda Rp 200.000)</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('kondisi_akhir')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="catatan" :value="__('Catatan Tambahan (Opsional)')" />
                                    <textarea id="catatan" name="catatan" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                                    <div class="flex justify-between items-center font-bold text-lg">
                                        <span>Total Denda Bayar:</span>
                                        <span id="total_bayar">Rp {{ number_format($denda_keterlambatan, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <a href="{{ route('petugas.monitoring.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</a>
                                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded font-bold hover:bg-green-700">
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
        function calculateTotal() {
            const dendaTelat = {{ $denda_keterlambatan }};
            const select = document.getElementById('kondisi_akhir');
            const dendaRusak = parseInt(select.options[select.selectedIndex].getAttribute('data-denda'));
            
            const total = dendaTelat + dendaRusak;
            
            // Format Rupiah
            const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total);
            
            document.getElementById('total_bayar').textContent = formatted;
        }
    </script>
</x-app-layout>
