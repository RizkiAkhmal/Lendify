<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-8">
                <!-- Sedang Dipinjam -->
    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Status Peminjaman Aktif</h3>
                <p class="text-xs text-gray-400">Daftar alat yang sedang digunakan oleh peminjam</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Alat & Jumlah</th>
                        <th class="px-6 py-4">Tgl Pinjam</th>
                        <th class="px-6 py-4">Tgl Kembali</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($dipinjam as $item)
                        @php
                            $telat = $item->hitungKeterlambatan();
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ $telat > 0 ? 'bg-red-50/50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wide">ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }}</div>
                                <div class="text-xs text-gray-500">{{ $item->jumlah }} Unit • <span class="italic text-gray-400">{{ $item->alat->merk }}</span></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y, H:i') : '-' }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">Waktu Ambil</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $item->tanggal_kembali_rencana->format('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">Deadline</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($telat > 0)
                                    <div class="flex flex-col">
                                        <span class="text-red-600 font-black text-sm uppercase">Terlambat</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700 w-fit">{{ $telat }} Hari</span>
                                    </div>
                                @else
                                    <div class="flex flex-col">
                                        <span class="text-green-600 font-bold text-xs uppercase italic tracking-tighter">Berjalan</span>
                                        <span class="text-[10px] text-gray-400 uppercase">On Schedule</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('petugas.monitoring.return.form', $item) }}" class="inline-flex items-center px-4 py-2 bg-[#1e1e2d] text-white rounded-lg text-[10px] font-bold uppercase hover:bg-black transition shadow-sm">
                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                                    Kembalikan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-500">Semua Terkendali!</h4>
                                    <p class="text-xs text-gray-400">Tidak ada alat yang sedang dipinjam saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
            </div>

        </div>
    </div>
</x-app-layout>
