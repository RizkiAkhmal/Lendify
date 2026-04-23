<x-app-layout>
    <x-slot name="header">
        Data Alat 
    </x-slot>

    <div class="card-metronic overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-4">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#009ef7] text-sm" placeholder="Cari alat...">
            </div>
            
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <a href="{{ route('admin.alat.export') }}" class="flex-1 sm:flex-none px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-sm flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </a>
                <a href="{{ route('admin.alat.create') }}" class="flex-1 sm:flex-none px-6 py-2 bg-[#009ef7] text-white rounded-lg font-bold hover:bg-[#0086d1] transition shadow-sm flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Alat
                </a>
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
                        <th class="px-6 py-4 text-center">Aksi</th>
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
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
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

                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center space-x-2">
                                    @if($alat->jumlah_rusak > 0)
                                        <a href="{{ route('admin.alat.repair.show', $alat) }}" class="p-2 rounded-lg bg-red-50 text-red-400 hover:text-red-600 hover:bg-red-100 transition" title="Selesaikan Perbaikan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.alat.show', $alat) }}" class="p-2 rounded-lg bg-gray-50 text-gray-400 hover:text-[#009ef7] hover:bg-blue-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.alat.edit', $alat) }}" class="p-2 rounded-lg bg-gray-50 text-gray-400 hover:text-[#009ef7] hover:bg-blue-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form id="delete-form-{{ $alat->id }}" action="{{ route('admin.alat.destroy', $alat) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $alat->id }}', 'Alat {{ $alat->nama_alat }} akan dihapus.')" class="p-2 rounded-lg bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Tidak ada data alat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($alats->hasPages())
            <div class="p-6 bg-white border-t border-gray-100">
                {{ $alats->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
