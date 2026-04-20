<x-app-layout>
    <x-slot name="header">
        Antrean Persetujuan
    </x-slot>

    <div class="card-metronic overflow-hidden">
    <div class="space-y-8">
        <!-- 1. Menunggu Persetujuan -->
        <div class="card-metronic overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Menunggu Persetujuan</h3>
                    <p class="text-xs text-gray-400">Daftar permintaan peminjaman baru</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat & Stok</th>
                            <th class="px-6 py-4">Keperluan</th>
                            <th class="px-6 py-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($pending as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }}</div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">REQ: {{ $item->jumlah }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-600 italic">"{{ $item->keperluan }}"</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        @if($item->alat->jumlah_tersedia >= $item->jumlah)
                                            <form action="{{ route('petugas.approval.approve', $item) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-[10px] font-bold uppercase hover:bg-green-600" onclick="return confirm('Setujui peminjaman ini?')">
                                                    SETUJUI
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-[10px] font-bold cursor-not-allowed">
                                                STOK KURANG
                                            </button>
                                        @endif

                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="bg-red-500 text-white px-3 py-1 rounded text-[10px] font-bold uppercase hover:bg-red-600">
                                                TOLAK
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-64 bg-white p-3 rounded-lg shadow-xl border border-gray-100 z-50">
                                                <form action="{{ route('petugas.approval.reject', $item) }}" method="POST">
                                                    @csrf
                                                    <textarea name="catatan_petugas" class="w-full text-xs p-2 border rounded mb-2" placeholder="Alasan penolakan..." required></textarea>
                                                    <button type="submit" class="w-full py-1.5 bg-red-500 text-white rounded text-[10px] font-bold uppercase">Konfirmasi</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Tidak ada pengajuan baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Siap Diambil (Approved) -->
        <div class="card-metronic overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#009ef7]">Siap Diambil</h3>
                    <p class="text-xs text-gray-400">Persetujuan selesai, menunggu pengambilan barang</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat</th>
                            <th class="px-6 py-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($ready as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->user->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $item->alat->nama_alat }} ({{ $item->jumlah }})</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('petugas.approval.pickup', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-[#009ef7] text-white px-4 py-1.5 rounded text-[10px] font-bold uppercase hover:bg-blue-600 shadow-sm" onclick="return confirm('Konfirmasi pengambilan barang?')">
                                            Barang Diambil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400">Tidak ada barang menunggu pengambilan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
