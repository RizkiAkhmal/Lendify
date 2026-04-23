<x-app-layout>
    <x-slot name="header">
        Laporan Pengembalian
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Card -->
        <div class="card-metronic p-6 bg-white overflow-visible">
            <form method="GET" action="{{ route('petugas.laporan.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Mulai Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-lg border-gray-200 focus:border-[#009ef7] focus:ring-0 text-sm py-2.5">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sampai Dengan</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-lg border-gray-200 focus:border-[#009ef7] focus:ring-0 text-sm py-2.5">
                </div>
                <div class="flex items-center space-x-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-[#1e1e2d] text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-black transition shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('petugas.laporan.index') }}" class="flex-1 md:flex-none px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition text-center">
                        Reset
                    </a>
                </div>
                <div class="w-full md:w-auto md:ml-auto">
                    <a href="{{ route('petugas.laporan.export', request()->all()) }}" class="flex items-center justify-center px-6 py-2.5 bg-green-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-green-700 transition shadow-lg shadow-green-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export 
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="card-metronic overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Detail Alat</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4">Status Akhir</th>
                            <th class="px-6 py-4">Total Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($laporan as $item)
                            <tr class="hover:bg-gray-50 transition cursor-default">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium uppercase">{{ $item->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase">{{ $item->jumlah }} Unit • Brand: {{ $item->alat->merk ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y, H:i') : '-' }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-tighter italic font-medium">Waktu Ambil</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">
                                        @if($item->status === 'rejected')
                                            <span class="text-red-400 font-normal italic">(Ditolak)</span>
                                        @else
                                            {{ $item->pengembalian && $item->pengembalian->tanggal_kembali_aktual ? $item->pengembalian->tanggal_kembali_aktual->format('d M Y') : '-' }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-tighter italic">Diserahkan ke Petugas</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        @if($item->status === 'rejected')
                                            <span class="text-[10px] font-black uppercase text-red-600">
                                                STATUS: DITOLAK
                                            </span>
                                            <span class="text-[10px] text-gray-400 italic mt-0.5">
                                                Alasan: {{ $item->catatan_petugas ?? 'Tidak ada catatan' }}
                                            </span>
                                        @else
                                            @php
                                                $kondisiClass = [
                                                    'baik' => 'text-green-600',
                                                    'rusak_ringan' => 'text-yellow-600',
                                                    'rusak_berat' => 'text-red-600',
                                                ];
                                                $kondisi = $item->pengembalian->kondisi_alat ?? 'baik';
                                            @endphp
                                            <span class="text-[10px] font-black uppercase {{ $kondisiClass[$kondisi] ?? 'text-gray-600' }}">
                                                Kondisi: {{ str_replace('_', ' ', $kondisi) }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 italic mt-0.5">
                                                {{ $item->pengembalian->catatan ?? 'Tidak ada catatan' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $isDenda = ($item->pengembalian->denda ?? 0) > 0;
                                    @endphp
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black {{ $isDenda ? 'text-[#009ef7]' : 'text-gray-300' }}">
                                            Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                                        </span>
                                        @if($isDenda)
                                            <span class="text-[9px] font-bold text-red-400 uppercase">
                                                {{ $item->pengembalian->keterlambatan_hari ?? 0 }} Hari Telat
                                            </span>
                                        @else
                                            <span class="text-[9px] font-medium text-gray-300 uppercase tracking-tighter">Lunas/Tanpa Denda</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-500 italic">Data Laporan Kosong</h4>
                                        <p class="text-xs text-gray-400 mt-1">Silakan sesuaikan filter tanggal atau lakukan proses pengembalian terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($laporan->hasPages())
                <div class="p-6 bg-[#f9fafb] border-t border-gray-100">
                    {{ $laporan->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
