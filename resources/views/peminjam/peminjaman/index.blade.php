<x-app-layout>
    <x-slot name="header">
        Riwayat Peminjaman Saya
    </x-slot>

    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi</h3>
                <p class="text-xs text-gray-400">Menampilkan seluruh riwayat pengajuan peminjaman Anda</p>
            </div>
            <a href="{{ route('peminjam.katalog.index') }}" class="px-5 py-2 bg-[#009ef7] text-white rounded-lg font-bold text-xs hover:bg-[#0086d1] transition shadow-sm">
                Pinjam Alat Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">Informasi Alat</th>
                        <th class="px-6 py-4">Waktu Peminjaman</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Keperluan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-[#009ef7] flex items-center justify-center font-bold mr-3 flex-shrink-0">
                                        {{ substr($item->alat->nama_alat, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-gray-900 truncate">{{ $item->alat->nama_alat }}</div>
                                        <div class="text-xs text-gray-400 font-medium">{{ $item->alat->kode_alat }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-semibold text-gray-700">
                                    <span class="text-gray-400">PINJAM:</span> {{ $item->tanggal_peminjaman ? $item->tanggal_peminjaman->format('d M Y') : '-' }}
                                </div>
                                <div class="text-xs font-semibold text-gray-700 mt-1">
                                    <span class="text-gray-400">TARGET:</span> {{ $item->tanggal_kembali_rencana ? $item->tanggal_kembali_rencana->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-50 text-yellow-600',
                                        'approved' => 'bg-blue-50 text-blue-600',
                                        'dipinjam' => 'bg-indigo-50 text-indigo-600',
                                        'selesai' => 'bg-green-50 text-green-600',
                                        'rejected' => 'bg-red-50 text-red-600',
                                    ];
                                    $statusColor = $statusColors[$item->status] ?? 'bg-gray-50 text-gray-600';
                                @endphp
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-wider {{ $statusColor }}">
                                    {{ $item->status }}
                                </span>

                                @if($item->status === 'selesai' && $item->pengembalian && ($item->pengembalian->denda > 0 || $item->pengembalian->kondisi_alat !== 'baik'))
                                    <div class="mt-3 space-y-1 bg-gray-50 p-2 rounded-lg border border-gray-100 shadow-sm">
                                        @if($item->pengembalian->kondisi_alat !== 'baik')
                                            <div class="flex justify-between items-center text-[9px]">
                                                <span class="text-gray-400 font-bold uppercase">Kondisi</span>
                                                <span class="text-red-500 font-black uppercase">{{ str_replace('_', ' ', $item->pengembalian->kondisi_alat) }}</span>
                                            </div>
                                        @endif
                                        
                                        @php
                                            $dendaTelat = ($item->pengembalian->keterlambatan_hari ?? 0) * 5000;
                                            $dendaRusak = ($item->pengembalian->denda ?? 0) - $dendaTelat;
                                        @endphp

                                        @if($dendaTelat > 0)
                                            <div class="flex justify-between items-center text-[9px]">
                                                <span class="text-gray-400 uppercase">Denda Telat</span>
                                                <span class="text-gray-700 font-bold">Rp {{ number_format($dendaTelat, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($dendaRusak > 0)
                                            <div class="flex justify-between items-center text-[9px]">
                                                <span class="text-gray-400 uppercase">Denda Rusak</span>
                                                <span class="text-gray-700 font-bold">Rp {{ number_format($dendaRusak, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($item->pengembalian->denda > 0)
                                            <div class="flex justify-between items-center text-[10px] border-t border-gray-200 pt-1 mt-1">
                                                <span class="font-bold text-gray-800 uppercase">Total</span>
                                                <span class="font-black text-[#009ef7]">Rp {{ number_format($item->pengembalian->denda, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-600 italic line-clamp-1 max-w-[200px]" title="{{ $item->keperluan }}">
                                    {{ $item->keperluan }}
                                </p>
                                @if($item->catatan_petugas)
                                    <p class="text-[10px] text-red-500 font-bold mt-1">
                                        NB: {{ $item->catatan_petugas }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Belum ada riwayat peminjaman.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($peminjaman->hasPages())
            <div class="p-6 bg-white border-t border-gray-100">
                {{ $peminjaman->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
