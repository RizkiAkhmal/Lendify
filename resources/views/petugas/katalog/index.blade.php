<x-app-layout>
    <x-slot name="header">
        Katalog Alat
    </x-slot>

    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
            <div class="relative w-full sm:w-64">
                <form action="{{ route('petugas.katalog.index') }}" method="GET">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#009ef7] text-sm" placeholder="Cari alat...">
                </form>
            </div>
            
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9fafb] text-gray-400 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Informasi Alat</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Stok</th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($alats as $alat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-500">
                                {{ ($alats->currentPage() - 1) * $alats->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 mr-4 overflow-hidden flex-shrink-0">
                                        @if($alat->foto)
                                            <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 border border-gray-100 rounded-lg">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-gray-900 truncate" title="{{ $alat->nama_alat }}">{{ $alat->nama_alat }}</div>
                                        <div class="text-xs text-gray-400">{{ $alat->kode_alat }} • {{ $alat->merk ?? 'No Brand' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-purple-50 text-purple-600">
                                    {{ $alat->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center justify-between w-32">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-400 uppercase font-bold">Ready</span>
                                            <span class="text-sm font-black text-gray-800">{{ $alat->jumlah_tersedia }}</span>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="text-xs text-gray-400 uppercase font-bold">Rusak</span>
                                            <span class="text-sm font-black text-red-500">{{ $alat->jumlah_rusak }}</span>
                                        </div>
                                    </div>
                                    <div class="w-32 h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden flex">
                                        @php
                                            $readyWidth = ($alat->jumlah_tersedia / max($alat->jumlah_total, 1)) * 100;
                                            $rusakWidth = ($alat->jumlah_rusak / max($alat->jumlah_total, 1)) * 100;
                                        @endphp
                                        <div class="h-full bg-[#009ef7]" style="width: {{ $readyWidth }}%"></div>
                                        <div class="h-full bg-red-400" style="width: {{ $rusakWidth }}%"></div>
                                    </div>
                                    <span class="text-[9px] text-gray-400 mt-1">Total: {{ $alat->jumlah_total }} Unit</span>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p>Data alat tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-[#f9fafb] border-t border-gray-100">
            {{ $alats->links() }}
        </div>
    </div>
</x-app-layout>
