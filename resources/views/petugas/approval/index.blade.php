<x-app-layout>
    <x-slot name="header">
        Antrean Persetujuan
    </x-slot>

    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Menunggu Persetujuan</h3>
                <p class="text-xs text-gray-400">Total {{ $peminjaman->total() }} permintaan pending ditemukan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Alat & Stok</th>
                        <th class="px-6 py-4">Durasi Pinjam</th>
                        <th class="px-6 py-4">Keperluan</th>
                        <th class="px-6 py-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-[#009ef7] flex items-center justify-center font-bold mr-3">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $item->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }}</div>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">REQ: {{ $item->jumlah }}</span>
                                    @if($item->alat->jumlah_tersedia >= $item->jumlah)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-green-100 text-green-600">STOK: {{ $item->alat->jumlah_tersedia }}</span>
                                    @else
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-red-100 text-red-600">STOK: {{ $item->alat->jumlah_tersedia }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-semibold text-gray-700">
                                    <span class="text-gray-400">MULAI:</span> {{ $item->tanggal_peminjaman->format('d/m/Y') }}
                                </div>
                                <div class="text-xs font-semibold text-gray-700 mt-1">
                                    <span class="text-gray-400">KEMBALI:</span> {{ $item->tanggal_kembali_rencana->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-600 italic line-clamp-2 max-w-[200px]" title="{{ $item->keperluan }}">
                                    "{{ $item->keperluan }}"
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2 max-w-[150px] mx-auto">
                                    @if($item->alat->jumlah_tersedia >= $item->jumlah)
                                        <form action="{{ route('petugas.approval.approve', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 px-3 bg-green-500 text-white rounded text-[11px] font-bold hover:bg-green-600 transition shadow-sm" onclick="return confirm('Setujui peminjaman ini?')">
                                                SETUJUI
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="w-full py-1.5 px-3 bg-gray-300 text-gray-500 rounded text-[11px] font-bold cursor-not-allowed">
                                            STOK KURANG
                                        </button>
                                    @endif

                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="w-full py-1.5 px-3 bg-red-500 text-white rounded text-[11px] font-bold hover:bg-red-600 transition shadow-sm">
                                            TOLAK
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-64 bg-white p-3 rounded-lg shadow-xl border border-gray-100 z-50">
                                            <form action="{{ route('petugas.approval.reject', $item) }}" method="POST">
                                                @csrf
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Alasan Penolakan</label>
                                                <textarea name="catatan_petugas" class="w-full text-xs p-2 border border-gray-200 rounded focus:border-red-500 focus:outline-none mb-2" rows="2" required placeholder="Tulis alasan..."></textarea>
                                                <button type="submit" class="w-full py-2 bg-red-500 text-white rounded text-[10px] font-bold uppercase tracking-widest hover:bg-red-600 transition">
                                                    Konfirmasi Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Tidak ada antrean pending.</span>
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
