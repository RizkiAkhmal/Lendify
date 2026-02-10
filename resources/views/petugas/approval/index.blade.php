<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah/Stok</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keperluan</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($peminjaman as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->alat->nama_alat }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->alat->kode_alat }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>Pinjam: {{ $item->tanggal_peminjaman->format('d/m/Y') }}</div>
                                            <div>Kembali: {{ $item->tanggal_kembali_rencana->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>Permintaan: {{ $item->jumlah }}</div>
                                            <div class="{{ $item->alat->jumlah_tersedia >= $item->jumlah ? 'text-green-600' : 'text-red-600' }}">
                                                Stok: {{ $item->alat->jumlah_tersedia }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ $item->keperluan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex flex-col gap-2 items-end">
                                                <form action="{{ route('petugas.approval.approve', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 w-full" onclick="return confirm('Setujui peminjaman ini? Stok alat akan berkurang.')">
                                                        Approve
                                                    </button>
                                                </form>

                                                <form action="{{ route('petugas.approval.reject', $item) }}" method="POST" class="flex flex-col gap-1 w-full">
                                                    @csrf
                                                    <input type="text" name="catatan_petugas" placeholder="Alasan tolak" class="text-xs border-gray-300 rounded px-2 py-1" required>
                                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 w-full" onclick="return confirm('Tolak peminjaman ini?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pengajuan peminjaman baru (pending).</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $peminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
