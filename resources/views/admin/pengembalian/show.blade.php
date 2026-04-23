<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengembalian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- User & Tool Info -->
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Informasi Peminjam</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase">Nama Peminjam</p>
                                <p class="text-sm font-medium text-gray-800">{{ $pengembalian->peminjaman->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase">Email</p>
                                <p class="text-sm font-medium text-gray-800">{{ $pengembalian->peminjaman->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Informasi Alat</h3>
                        <div class="space-y-4">
                            @if($pengembalian->peminjaman->alat->foto)
                                <img src="{{ Storage::url($pengembalian->peminjaman->alat->foto) }}" class="w-full h-40 object-cover rounded-lg">
                            @endif
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Nama Alat</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $pengembalian->peminjaman->alat->nama_alat }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Kode Alat</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $pengembalian->peminjaman->alat->kode_alat }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Jumlah Pinjam</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $pengembalian->peminjaman->jumlah }} Unit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Transaction Detail -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                        <div class="flex justify-between items-center border-b pb-6 mb-6">
                            <h3 class="text-2xl font-black text-gray-900">Rincian Transaksi Selesai</h3>
                            <span class="px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-xs font-bold uppercase tracking-widest border border-green-100">Selesai</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left Column: Dates -->
                            <div class="space-y-6">
                                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                                    <div class="mb-4">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Waktu Pinjam (Mulai)</p>
                                        <p class="text-sm font-black text-gray-800">{{ $pengembalian->peminjaman->tanggal_pinjam ? $pengembalian->peminjaman->tanggal_pinjam->translatedFormat('d F Y, H:i') : '-' }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Rencana Kembali</p>
                                        <p class="text-sm font-black text-gray-800">{{ $pengembalian->peminjaman->tanggal_kembali_rencana->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Waktu Kembali (Selesai)</p>
                                        <p class="text-sm font-black text-indigo-600">{{ $pengembalian->tanggal_kembali_aktual->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-2">Keperluan</p>
                                    <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg italic border-l-4 border-gray-200">"{{ $pengembalian->peminjaman->keperluan }}"</p>
                                </div>
                            </div>

                            <!-- Right Column: Results & Fines -->
                            <div class="space-y-6">
                                <div class="p-5 rounded-2xl {{ $pengembalian->denda > 0 ? 'bg-red-50/50 border border-red-100' : 'bg-green-50/50 border border-green-100' }}">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Kondisi Akhir</span>
                                        <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase {{ $pengembalian->kondisi_alat === 'baik' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                            {{ str_replace('_', ' ', $pengembalian->kondisi_alat) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Keterlambatan</span>
                                        <span class="text-sm font-black {{ $pengembalian->keterlambatan_hari > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $pengembalian->keterlambatan_hari }} Hari
                                        </span>
                                    </div>

                                    <div class="pt-4 border-t border-dashed {{ $pengembalian->denda > 0 ? 'border-red-200' : 'border-green-200' }}">
                                        <div class="flex justify-between items-end">
                                            <span class="text-xs font-black text-gray-500 uppercase">Total Denda</span>
                                            <span class="text-2xl font-black {{ $pengembalian->denda > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase mb-2">Catatan Pengembalian</p>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg min-h-[80px] border border-gray-100">
                                        {{ $pengembalian->catatan ?? 'Tidak ada catatan tambahan.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-between items-center">
                            <div class="text-xs text-gray-400 italic">
                                Diproses oleh: <span class="font-bold text-gray-500">{{ $pengembalian->peminjaman->petugas->name ?? 'System' }}</span> pada {{ $pengembalian->created_at->format('d/m/Y H:i') }}
                            </div>
                            <a href="{{ route('admin.pengembalian.index') }}" class="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition shadow-lg">Kembali ke Daftar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
